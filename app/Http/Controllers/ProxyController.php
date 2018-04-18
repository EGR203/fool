<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller {

	public function knownProxy(Request $request, $name) {
		$proxy = Proxy::where('name', $name)->first();
		if (!$proxy) {
			return "<h1> I DONT NO THAT NAME {$name}</h1>";
		}

		return $proxy->makeRequest($request->all());
	}

	public function unknownProxy(Request $request) {
		$method  = strtolower($request->get('method', ''));
		$url     = $request->get('url');
		$data    = $request->get('data', []);
		$headers = $request->get('headers', []);

		if (!in_array($method, ['get', 'post', 'put', 'option'])) {
			return \Response::make('Invalid method', 404);
		}
		if (!$url) {
			return \Response::make('Where is URL?!', 404);
		}
		try {
			$options = ['timeout' => 3, 'connect_timeout' => 2];
			if ($method == 'get') {
				$res = \Requests::$method($url, $headers, $options);
			}
			else {
				$res = \Requests::$method($url, $headers, $data, $options);
			}
			$msg = isset($data['full']) && $data['full'] ? $res->body : substr($res->body, 0, 256);

			return \Response::make($msg, $res->status_code);
		}
		catch (\Requests_Exception_HTTP $e) {
			return \Response::make($e->getMessage(), $e->getCode());
		}
		catch (\Exception $e) {
			return \Response::make($e->getMessage(), 404);
		}
	}
}
