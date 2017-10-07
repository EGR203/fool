<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class NowLessonCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$groups = Group::getDefaultGroups();
		$date = Carbon::now();
		$msg = $this->prepareNearestLessonForGroupsByDate($groups, $date, True);
		return $msg;

	}
}