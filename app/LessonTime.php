<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\LessonTime
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lesson[] $lessons
 * @mixin \Eloquent
 * @property int $id
 * @property string $start_lesson
 * @property string $stop_lesson
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LessonTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LessonTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LessonTime whereStartLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LessonTime whereStopLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LessonTime whereUpdatedAt($value)
 */
class LessonTime extends Model
{
	protected $fillable = [
		'start_lesson',
		'stop_lesson'
	];
	public function lessons() {
		return $this->hasMany('App\Lesson', 'lesson_number_id', 'id');
	}

	public static function getByDate(Carbon $date = null) {
		$date = $date ?? Carbon::now();
		$time = $date->toTimeString();
		$run_lesson = self::where('stop_lesson','>=', $time)->where('start_lesson', '<=', $time)->first();
		return $run_lesson;
	}

	public function toStr() {
		$from = $this->start_lesson;
		$to = $this->stop_lesson;
		$str = substr($from, 0, 5) . " - ";
		$str .= substr($to, 0, 5);
		return $str;
	}
}
