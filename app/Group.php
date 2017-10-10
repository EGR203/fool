<?php

namespace App;

use App\Utils\DateTimeUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
	protected $fillable = [
		'group',
		'undergroup'
	];

	public function lessons() {
		return $this->hasMany( 'App\Lesson' );
	}

	public static function getDefaultGroups() {
		$default = config( 'vk.group_default' );
		$groups  = self::all()->where( 'group', $default );

		return $groups;
	}

	/**
	 * Ищет ближайшую лекции для задданой даты, если дата не задана
	 * по умолчанию будет использована текущая дата
	 * @param Carbon|null $date
	 * @param bool $strict Возвращать null, если сегодня нет лекций
	 *
	 * @return Lesson|null
	 */
	public function getNearestLessonByDate( Carbon $date = null, bool $strict = false) {
		$date    = $date ?? Carbon::now();
		$time    = $date->toTimeString();

		$lessons_in_date = self::lessonsByDate( $date, true );
		if ( ! count( $lessons_in_date ) ) {
			$date->addDay();
			$date->hour = 0;

			return ($strict? null : self::getNearestLessonByDate( $date ));
		}
		$lts = [];
		foreach ( $lessons_in_date as $l ) {
			$lt    = $l->lessonTime()->first();
			$lts[] = $lt;
		}
		# Лекции еще не начались
		if ( $time < $lts[0]->start_lesson ) {
			return $lessons_in_date[0];
		}
		# Лекции уже закончились
		if ( $time > $lts[ count($lts) - 1 ]->stop_lesson ) {
			$date->addDay();
			$date->hour = 0;

			return ($strict? null : self::getNearestLessonByDate( $date ));
		}

		# Мы на лекции
		foreach ( $lts as $i => $lt ) {
			if ( $lt->start_lesson <= $time && $time <= $lt->stop_lesson ) {
				return $lessons_in_date[ $i ];
			}
		}

		# Если у нас всего 1 лекция, смотрим че на завтра
		if ( count( $lessons_in_date ) < 2 ) {
			$date->addDay();
			$date->hour = 0;

			return ($strict? null : self::getNearestLessonByDate( $date ));
		}

		# Мы в перерыве между лекциями
		for ( $i = 0; $i < count( $lts ) - 1; $i ++ ) {
			if ( $lts[ $i ]->stop_lesson <= $time && $time <= $lts[ $i + 1 ]->start_lesson ) {
				return $lessons_in_date[ $i + 1 ];
			}
		}

		$date->addDay();
		$date->hour = 0;

		return ($strict? null : self::getNearestLessonByDate( $date ));
	}

	public function lessonsByDate( Carbon $date = null, $check_parity = true ) {
		$date    = $date ?? Carbon::now();
		$is_odd = DateTimeUtils::isOddWeekOfStudy($date);
		# Карбон начинает отчет дня недели с воскресенья
		$weekday = ($date->dayOfWeek - 1 ) % 7 ;
		if (!$check_parity){
			$lessons = self::lessons()->where( 'day', $weekday )->get();
		} else {
			$lessons = self::lessons()->where( 'day', $weekday )->where('is_odd', $is_odd)->get();
		}

		return $lessons;
	}

}
