<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProxiesLog
 *
 * @property int $id
 * @property int $proxy_id
 * @property string $request
 * @property string $response
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Proxy $proxy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereProxyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProxiesLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
