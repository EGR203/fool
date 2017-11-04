<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Lesson
 *
 * @property-read \App\Group $group
 * @property-read \App\LessonTime $lessonTime
 * @mixin \Eloquent
 * @property int $id
 * @property int $day
 * @property int $lesson_number_id
 * @property int $group_id
 * @property string|null $classroom
 * @property string|null $type
 * @property string|null $teacher
 * @property string|null $name
 * @property int $is_odd
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereClassroom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereIsOdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereLessonNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereTeacher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Lesson whereUpdatedAt($value)
 */
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
	 public function toShortStr() {
		 $str = "{$this->lessonTime()->first()->toStr()} {$this->classroom} {$this->teacher} ";
		 return $str;
	 }
}
