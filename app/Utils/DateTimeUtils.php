<?php

namespace App\Utils;


use Carbon\Carbon;

class DateTimeUtils {
	private function __construct() {
	}


	public static function getBeginningOfStudy() {
		$study = config('vk.beginning_of_study');
		return $study;
	}

	public static function getWeekOfStudy(Carbon $date=null) {
		$date = $date ?? Carbon::now();
		$week = $date->diffInWeeks(self::getBeginningOfStudy()) + 1;
		return $week;
	}

	public static function isOddWeekOfStudy(Carbon $date=null) {
		$date = $date ?? Carbon::now();
		$beg_study = self::getWeekOfStudy($date);
		$isOdd = $beg_study % 2;

		return $isOdd;
	}
}