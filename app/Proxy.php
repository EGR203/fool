<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Proxy
 *
 * @mixin \Eloquent
 * @property int                 $id
 * @property string              $ip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereUpdatedAt($value)
 * @property string              $path
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy wherePath($value)
 * @property string              $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereName($value)
 */
class Proxy extends Model {

	protected $fillable = [
		'ip',
		'path',
		'name',
	];

	public function logs() {
		return $this->hasMany( 'App\ProxiesLog' );
	}
	public function getLogs() {
		return $this->logs()->get();
	}

	public function makeRequest(array $params = array()): string {
		$url = $this->getUrl();

		$boundary  = uniqid();
		$delimiter = '-------------' . $boundary;
		$post_data = $this->buildDataFiles($boundary, $params);


		$ch = \curl_init($url);

		\curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		\curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		\curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0");
		\curl_setopt($ch, CURLOPT_POST, true);
		\curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		\curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: multipart/form-data; boundary=" . $delimiter
		));

		$response = \curl_exec($ch);
		\curl_close($ch);
		if (!$response) {
			return '<h1> ERROR DURING CURL (Have not response)</h1>';
		}
		$this->log(print_r($params,1), $response);

		return $response;

	}

	protected function log($request, $response) {
		$pl = new ProxiesLog();
		$pl->proxy_id = $this->id;
		$pl->request = $request;
		$pl->response = $response;
		$pl->save();
		@\Log::info("Url: " . $this->getUrl() . " :::: Request: " . $request . " :::: Response: $response");
	}

	public function getUrl(): string {
		if (!preg_match('/^http\.*/', $this->ip)) {
			$url = 'http://' . $this->ip . $this->path;
		}
		else {
			$url = $this->ip . $this->path;
		}

		return $url;
	}

	protected function buildDataFiles($boundary, $fields) {
		$data = '';
		$eol  = "\r\n";

		$delimiter = '-------------' . $boundary;

		foreach ($fields as $name => $content) {
			$data .= "--" . $delimiter . $eol
			         . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
			         . $content . $eol;
		}


		$data .= "--" . $delimiter . "--" . $eol;


		return $data;
	}
}
