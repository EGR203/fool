<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class TodayLessonsCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$date = Carbon::now();
		$msg = $this->prepareAllLessonsForGroupsByDate($groups, $date, true);
		return $msg;
	}

	public  static function getInfo(): string {
		return "Расписание на сегодня с учетом четности недели";
	}
}