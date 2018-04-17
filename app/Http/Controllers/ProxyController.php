<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller {

//	public function register( Request $request, $name) {
//		$ip   = $request->get( 'ip' );
//		$path = $request->post( 'path' );
//		if ( ! strlen( $ip ) || ! strlen( $path ) ) {
//			return 'no';
//		}
//		if ( ! is_null( $name ) ) {
//			$proxy = Proxy::firstOrNew( [ 'name' => $name ] );
//		} else {
//			$proxy = new Proxy();
//		}
//		$proxy->ip   = $ip;
//		$proxy->path = $path;
//		$proxy->name = $name;
//		$proxy->touch();
//		$proxy->save();
//
//		return 'ok';
//	}

	public function doProxy(Request $request, $name) {
		$proxy = Proxy::where('name', $name)->first();
		if (!$proxy) {
			return "<h1> I DONT NO THAT NAME {$name}</h1>";
		}

		return $proxy->makeRequest($request->all());
	}

}
