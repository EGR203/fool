<?php

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\Request;

class ProxyController extends Controller {
	//
	public function register( Request $request ) {
		$ip   = $request->get( 'ip' );
		$path = $request->post( 'path' );
		if ( ! strlen( $ip ) || ! strlen( $path ) ) {
			return 'no';
		}
		$proxy       = Proxy::firstOrNew( [ 'ip' => $ip ] );
		$proxy->ip   = $ip;
		$proxy->path = $path;
		$proxy->save();

		return 'ok';
	}

	public function doProxy( Request $request ) {
		$proxy = Proxy::oldest()->first();
		$url   = 'http://' . $proxy->ip . $proxy->path;

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:56.0) Gecko/20100101 Firefox/56.0" );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $request->post() ) );

		$response = curl_exec( $ch );
		curl_close( $ch );

		echo $response;
		file_put_contents( 'connect.log', 'Took from ' . $_SERVER['REMOTE_ADDR']
		                                  . ' : response: ' . $response .
		                                  "\n\$_POST: " . print_r( $_POST, true ) . "\n", FILE_APPEND );
	}
}