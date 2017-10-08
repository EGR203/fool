<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\BotLogic\CommandFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ATehnix\VkClient;

class HandleVkMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request_from_vk;
	public function __construct($request_from_vk)
	{
		$this->request_from_vk = $request_from_vk;
	}

	private function getAccessToken(){
		return config('vk.access_token');
	}
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$request_from_vk = $this->request_from_vk;

		if(is_array($request_from_vk) && count($request_from_vk) > 0){
			Log::info("HandleVkMessage: params: " . print_r($request_from_vk,true));
			$msg = $this->formMsg($request_from_vk['object']);
			$api = new VkClient\Client;
			$api->setDefaultToken($this->getAccessToken());
			$request = new VkClient\Requests\Request('messages.send', [
				'user_id' => $request_from_vk['object']['user_id'],
				'message' => $msg,
			]);
			$execute = VkClient\Requests\ExecuteRequest::make([$request]);
			$response = $api->send($execute);
			Log::info("HandleVkMessage: For user_id :"
			          .$request_from_vk['object']['user_id'] . " response: " . print_r($response, 1));
		}
		else {
			Log::error("HandleVkMessage: For " . $this->request->ip() . " can`t parse request params");
		}
	}

	public function formMsg($object){
		$cmd = CommandFactory::getCommand($object);
		$msg = $cmd->execute();
		return $msg;
	}
}
