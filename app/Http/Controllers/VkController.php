<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\HandleVkMessage;

class VkController extends Controller
{
	/**
	 * Проводим верификацию URL
	 *
	 * @return mixed
	 */
	private function getConfirmToken()
	{
		return config('vk.confirm_token');
	}

	public function index(Request $request){
		$event_type = $request->input('type');


		switch ($event_type) {
			case 'confirmation':
				return $this->getConfirmToken();
			case 'message_new':
				$this->dispatch( new HandleVkMessage($request->input()));
				return 'ok';
			default:
				return 'ok';
		}
	}
}
