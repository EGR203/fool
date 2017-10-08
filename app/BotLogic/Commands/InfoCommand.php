<?php
namespace App\Console\Commands;

use App\BotLogic\Command;
use App\BotLogic\CommandFactory;
use App\BotLogic\UtilsCommand;
use App\Lesson;
use App\Group;
use Carbon\Carbon;

class InfoCommand extends Command {

	use UtilsCommand;

	public function execute(): string {
		$msg = "Доступные комманды: \\n";
		$acs = CommandFactory::getAvailableCommands();
		foreach ($acs as $name => $cmd) {
			$val = $cmd::getInfo();
			if ($val && $name != 'default') {
				$msg .= "* $name - $val \\n";
			}
		}
		return $msg;
	}

	public  static function getInfo(): string {
		return "Показывает справку по командам";
	}
}