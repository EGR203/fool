<?php

namespace App\BotLogic;


abstract class Command {
	protected $object;
	public function __construct(array $object) {
		$this->object = $object;
	}
	abstract public function execute(): string;
}