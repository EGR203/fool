<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {
	protected $fillable = [
		'day',
		'lesson_number_id',
		'group_id',
		'classroom',
		'type',
		'teacher',
		'name',
		'is_odd',
	];

	public function group() {
		return $this->belongsTo( 'App\Group' );
	}

	public function lessonTime() {
		return $this->belongsTo( 'App\LessonTime', 'lesson_number_id', 'id' );
	}

	public function toStr() {
		$str = "№{$this->lesson_number_id} {$this->lessonTime()->first()->toStr()} {$this->type}  в "
		       ."{$this->classroom} {$this->teacher} {$this->name} ";

		return $str;
	}
}
