<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class NextLessonCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$date = Carbon::now();
		$date->addHour(1);
		$date->addMinutes(30);
		$msg = $this->prepareNearestLessonForGroupsByDate($groups, $date, True);
		return $msg;
	}

	public  static function getInfo(): string {
		return "Следующая лекция на сегодня";
	}
}