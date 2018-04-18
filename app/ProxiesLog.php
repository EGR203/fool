<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProxiesLog extends Model
{
	protected $fillable = [
		'request',
		'response'
	];

	public function proxy() {
		return $this->belongsTo( 'App\Proxy' );
	}

	public function getProxy() {
		return $this->proxy()->first();
	}
}
