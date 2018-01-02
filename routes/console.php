<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('upUserToAdmin {mail}', function ($mail) {
	$user = \App\User::whereEmail($mail)->first();
	if ($user) {
		$user->is_admin = 1;
		$user->save();
		$this->comment('ok');
	} else {
		$this->comment("user with e-mail {$mail} not found");
	}
})->describe('up user to admin');
