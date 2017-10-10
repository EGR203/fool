<?php
namespace App\Console\Commands;

use App\BotLogic\Command;

class FuckOffCommand extends Command {
	public function execute(): string {
		return 'Добрый человек, иди лесом!';
	}

	public  static function getInfo(): string {
		return "";
	}
}