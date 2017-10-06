<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class TodayLessonCommand extends Command {
	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$lessons_info = [];
		$is_empty = True;
		$date = Carbon::now();
		foreach ($groups as $undergroup) {
			$lesson =  $undergroup->getNearestLessonByDate($date, True);
			if ( $lesson ) {
				$lessons_info[] = $lesson->toStr();
				$is_empty = false;
			}
		}
		if ($is_empty) {
			return "Сегодня нет пар";
		}

		if (count($lessons_info) > 1) {
			for ($i = count($lessons_info) -1 ; $i >= 1 ; $i--) {
				if($lessons_info[$i] == $lessons_info[$i-1]) {
					$lessons_info[$i] = 'Так же';
				}
			}
		}
		$msg = '';
		foreach ($lessons_info as $i => $li) {
			$group_num = $i+1;
			$msg .= "Для $group_num группы: " . $li . '\n';
		}
		return $msg;

	}
}