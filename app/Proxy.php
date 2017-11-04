<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Proxy
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $ip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proxy whereUpdatedAt($value)
 */
class Proxy extends Model {

	protected $fillable = [
		'ip'
	];
}
