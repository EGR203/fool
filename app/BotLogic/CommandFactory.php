<?php

namespace App\BotLogic;

use App\Console\Commands;

class CommandFactory {
	protected static $commands_available = [
		'default' => Commands\NearestLessonCommand::class,

		'ближайшая' => Commands\NearestLessonCommand::class,
		'сейчас' => Commands\NowLessonCommand::class,
		'следующая' => Commands\NextLessonCommand::class,

		'неделя' => Commands\ParityCommand::class,
		'четность' => Commands\ParityCommand::class,

		'староста' => Commands\StarostaCommand::class,

		'сегодня' => Commands\TodayLessonsCommand::class,
		'завтра' => Commands\TomorrowLessonsCommand::class,
		'послезавтра' => Commands\DayAfterTomorrowLessonsCommand::class,

		'понедельник' => Commands\MondeyLessonsCommand::class,
		'вторник' => Commands\TuesdayLessonsCommand::class,
		'среда' => Commands\WednesdayLessonsCommand::class,
		'четверг' => Commands\ThursdayLessonsCommand::class,
		'пятница' => Commands\FridayLessonsCommand::class,
		'суббота' => Commands\SaturdayLessonsCommand::class,

		'?' => Commands\InfoCommand::class,
		'помощь' => Commands\InfoCommand::class,

	];
	protected static function commandsToLower() {
		$ca = self::$commands_available;
		$new_ca = [];
		foreach ($ca as $name => $class) {
			$new_ca[$name] = $class;
		}
		self::$commands_available = $new_ca;
	}

	public static function getCommand(array $object): Command{
		self::commandsToLower();
		$ca = self::$commands_available;
		$body = $object['body'];
		$clear_body = preg_replace('/\s+/', ' ', $body);
		$token = self::findToken($clear_body);
		$reflect = new \ReflectionClass($ca[$token]);
		$cmd = $reflect->newInstance($object);
		return $cmd;
	}

	protected static function findToken(string $subject): string {
		$subject = strtolower($subject);
		$words = explode(' ', $subject);
		if (count($words)  == 0) {
			return 'default';
		}
		$ca = self::$commands_available;
		foreach ($words as $word) {
			if (key_exists($word, $ca)) {
				return $word;
			}
		}
		$tokens = array_keys($ca);
		$best_sim = 0;
		$best_token = 'default';
		foreach ($words as $word) {
			foreach ($tokens as $token) {
				similar_text( $word, $token, $percent );
				if ($percent > $best_sim){
					$best_sim = $percent;
					$best_token = $token;
				}
			}
		}

		if ($best_sim < 60) {
			return 'default';
		}
		return $best_token;
	}

	public static function getAvailableCommands(){
		return self::$commands_available;
	}
}