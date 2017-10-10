<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\Utils\DateTimeUtils;
use Carbon\Carbon;

class ParityCommand extends Command {
	public function execute(): string {
		$week_of_study = DateTimeUtils::getWeekOfStudy();
		$week_of_year = Carbon::now()->weekOfYear;
		$is_odd = DateTimeUtils::isOddWeekOfStudy();
		$msg = $is_odd ? "Сейчас нечетная учебная неделя" : "Сейчас четная учебная неделя";
		$msg .= " : #{$week_of_study} \\n И #{$week_of_year} неделя в году";

		return $msg;
	}

	public  static function getInfo(): string {
		return "Четность текущей учебной недели и её номер";
	}
}