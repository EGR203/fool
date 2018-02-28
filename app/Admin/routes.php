<?php
Route::get('', [
	'as' => 'admin.dashboard',
	function () {
		$content = 'Define your dashboard here.';

		return AdminSection::view($content, 'Dashboard');
	}
]);

Route::get('information', [
	'as' => 'admin.information',
	function () {
		$content = 'Define your information here.';

		return AdminSection::view($content, 'Information');
	}
]);

Route::get('activate/{id}', [
	'as' => 'admin.schedule.activate',
	function ($id) {

		$errors = null;
		try {
			if (!$schedule = \App\Schedule::find($id)) {
				return false;
			}
			$schedule->activate();
		}
		catch (Exception $e) {
			return redirect(route('admin.model', [
				'adminModel' => 'schedules',
			]))->with('error_message', $e->getMessage());
		}

		return redirect(route('admin.model', [
			'adminModel' => 'schedules',
		]))->with('success_message', "Таблица обновлена");


	}
]);

