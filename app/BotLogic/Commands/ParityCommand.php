<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\Utils\DateTimeUtils;
use Carbon\Carbon;

class ParityCommand extends Command {
	public function execute(): string {
		$week_of_year = DateTimeUtils::getWeekOfStudy();
		$is_odd = DateTimeUtils::isOddWeekOfStudy();
		$msg = $is_odd ? "Сейчас нечетная неделя" : "Сейчас четная неделя";
		$msg .= " ({$week_of_year})";
		return $msg;
	}

	public  static function getInfo(): string {
		return "Показывает четность текущей недели";
	}
}