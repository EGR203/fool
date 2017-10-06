<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Group;
use App\LessonTime;
use App\Lesson;
use Illuminate\Support\Facades\DB;

class ParseSchedule extends Command
{
	protected $parsed_schedule;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:parse-vk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run python parser lessons schedule and add info in db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getScriptPath(){
    	return config("vk.python_script_path");
    }

    private function getTablePath(){
    	return config("vk.table_path");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->parse();
    	if (! $this->parsed_schedule) {
    		throw new \Exception("have not parsed data");
	    }
		$this->removeExistingSchedule();
	    $this->save();
    }
    private function removeExistingSchedule(){
	    DB::table('groups')->delete();
	    DB::table('lessons')->delete();
    }

    private function save(){
    	foreach ($this->parsed_schedule as $group_name => $undergroup) {
    		foreach ($undergroup as $undergroup_i => $undergroup_val) {
    			$group = Group::firstOrNew([
    				'group' => $group_name,
				    'undergroup' => $undergroup_i
			    ]);
    			$group->save();
    			foreach ($undergroup_val as $day => $day_lessons){
    				foreach ($day_lessons as $lesson_number => $lesson){
    					foreach ($lesson as $is_odd => $lesson_info){
    						if (count($lesson_info) == 0) {
    							continue;
						    }
							$lesson_model = new Lesson([
								'teacher' => $lesson_info['teacher'],
								'classroom' => $lesson_info['classroom'],
								'name' => $lesson_info['name'],
								'type' => $lesson_info['type'],
								'is_odd' => ($is_odd == '1'),
								'day' => $day,
								'lesson_number_id' => $lesson_number,
								'group_id' => $group->getKey('id'),
							]);

						    $lesson_model->save();
					    }
				    }
			    }
		    }
	    }
    }

    private function parse()
    {
	    $process = new Process("python3 {$this->getScriptPath()} \"{$this->getTablePath()}\"" );
	    $process->run();

	    if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
	    }
	    $out = $process->getOutput();
	    $json = json_decode($out, true);
	    $this->parsed_schedule = $json;
    }
}
