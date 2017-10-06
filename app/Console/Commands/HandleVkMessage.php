<?php

namespace App\Console\Commands;

use App\BotLogic\CommandFactory;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ATehnix\VkClient;

class HandleVkMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:handle-msg';
    protected $request;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle Vk Message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
    	$this->request = $request;
        parent::__construct();
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
		$request_from_vk = $this->request->input();

		if(is_array($request_from_vk) && count($request_from_vk) > 0){
			Log::info("HandleVkMessage: For " . $this->request->ip() . ", params: " . print_r($request_from_vk,true));
			$msg = $this->formMsg($request_from_vk['object']);
			$api = new VkClient\Client;
			$api->setDefaultToken($this->getAccessToken());
			$request = new VkClient\Requests\Request('messages.send', [
				'user_id' => $request_from_vk['object']['user_id'],
				'message' => $msg,
			]);
			$execute = VkClient\Requests\ExecuteRequest::make([$request]);
			$response = $api->send($execute);
			Log::info("HandleVkMessage: For " . $this->request->ip() . " user_id :"
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
