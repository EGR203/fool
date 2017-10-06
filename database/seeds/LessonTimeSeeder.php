<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('lesson_times')->delete();
    	$times = [
		    ["08:30:00", "10:00:00"],
		    ["10:10:00", "11:40:00"],
		    ["11:50:00", "13:20:00"],
		    ["13:30:00", "15:00:00"],
		    ["15:10:00", "16:40:00"],
		    ["16:50:00", "18:20:00"],
		    ["18:30:00", "20:00:00"]
	    ];
	    for ($i = 0; $i < count($times); $i++){
		    DB::table('lesson_times')->insert([
			    'id' => $i,
			    'start_lesson' => $times[$i][0],
			    'stop_lesson' => $times[$i][1],
			    'created_at' => \Carbon\Carbon::now(),
		        'updated_at' => \Carbon\Carbon::now()
		    ]);
	    }
    }
}
