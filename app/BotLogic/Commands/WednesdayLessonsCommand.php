<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class WednesdayLessonsCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$date = Carbon::now();
		while (!$date->isWednesday()){
			$date->addDay();
		}
		$msg = $this->prepareAllLessonsForGroupsByDate($groups, $date, False);
		return $msg;

	}
}