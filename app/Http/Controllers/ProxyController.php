<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller {
	//
	public function register( Request $request, $name) {
		$ip   = $request->get( 'ip' );
		$path = $request->post( 'path' );
		if ( ! strlen( $ip ) || ! strlen( $path ) ) {
			return 'no';
		}
		if ( ! is_null( $name ) ) {
			$proxy = Proxy::firstOrNew( [ 'name' => $name ] );
		} else {
			$proxy = new Proxy();
		}
		$proxy->ip   = $ip;
		$proxy->path = $path;
		$proxy->name = $name;
		$proxy->touch();
		$proxy->save();

		return 'ok';
	}

	public function doProxy( Request $request, $name ) {
		$proxy = Proxy::where('name', $name)->first();
		if(!$proxy){
			return "<h1> I DONT NO THAT NAME {$name}</h1>";
		}

		if (!preg_match('/^http\.*/', $proxy->ip)){
			$url = 'http://' . $proxy->ip . $proxy->path ;
		} else {
			$url = $proxy->ip . $proxy->path;
		}

		$boundary = uniqid();
		$delimiter = '-------------' . $boundary;
		$post_data = $this->build_data_files($boundary, $request->all());


		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0" );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: multipart/form-data; boundary=" . $delimiter
		));

		$response = curl_exec( $ch );
		curl_close( $ch );
		if ( ! $response ) {
			return '<h1> ERROR DURING CURL (Have not response)</h1>';
		}
		@\Log::info("Url: $url :::: Request: " . print_r($request->all(), 1 ) . " :::: Response: $response");

		return $response;
	}

	protected function build_data_files($boundary, $fields){
		$data = '';
		$eol = "\r\n";

		$delimiter = '-------------' . $boundary;

		foreach ($fields as $name => $content) {
			$data .= "--" . $delimiter . $eol
			         . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
			         . $content . $eol;
		}


		$data .= "--" . $delimiter . "--".$eol;


		return $data;
	}
}
