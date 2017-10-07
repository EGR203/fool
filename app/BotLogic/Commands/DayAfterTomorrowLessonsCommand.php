<?php

namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class DayAfterTomorrowLessonsCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$date   = Carbon::now();
		$date->addDay( 2 );
		$date->hour = 0;
		$msg        = $this->prepareAllLessonsForGroupsByDate( $groups, $date, true );

		return $msg;

	}
}
