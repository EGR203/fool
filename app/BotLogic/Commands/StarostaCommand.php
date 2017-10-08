<?php
namespace App\Console\Commands;

use App\BotLogic\Command;

class StarostaCommand extends Command {
	public function execute(): string {
		return 'собака';
	}

	public  static function getInfo(): string {
		return "";
	}
}