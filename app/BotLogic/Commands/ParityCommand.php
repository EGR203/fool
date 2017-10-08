<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use Carbon\Carbon;

class ParityCommand extends Command {
	public function execute(): string {
		$week_of_year = Carbon::now()->weekOfYear;
		$is_odd = ($week_of_year) % 2;
		$msg = $is_odd ? "Сейчас нечетная неделя" : "Сейчас четная неделя";
		$msg .= " ({$week_of_year})";
		return $msg;
	}

	public  static function getInfo(): string {
		return "Показывает четность текущей недели";
	}
}