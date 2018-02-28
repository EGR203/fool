<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;

/**
 * App\Schedule
 *
 * @property bool                $is_activate
 * @property int                 $id
 * @property string              $file
 * @property string              $label
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Schedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schedule extends Model {
	protected $fillable = [
		'is_activate',
		'file',
		'label',
	];

	public function diactivate() {
		$this->is_activate = false;
		$this->save();
	}

	public function activate() {

		$path    = base_path('public') . DIRECTORY_SEPARATOR . $this->file;
		$script  = config("vk.python_script_path");
		$process = new Process("python3 $script \"$path\"");
		$process->run();

		if (!$process->isSuccessful()) {
			throw new ProcessFailedException($process);
		}
		$out  = $process->getOutput();
		$json = json_decode($out, true);
		if (!$json) {
			throw new \Exception("Can`t parse json: $out");
		}


		$activates = Schedule::where('is_activate', true)->get();
		foreach ($activates as $i => $schedule) {
			$schedule->diactivate();
		}

		DB::table('groups')->delete();
		DB::table('lessons')->delete();

		foreach ($json as $group_name => $undergroup) {
			foreach ($undergroup as $undergroup_i => $undergroup_val) {
				$group = Group::firstOrNew([
					'group'      => $group_name,
					'undergroup' => $undergroup_i
				]);
				$group->save();
				foreach ($undergroup_val as $day => $day_lessons) {
					foreach ($day_lessons as $lesson_number => $lesson) {
						foreach ($lesson as $is_odd => $lesson_info) {
							if (count($lesson_info) == 0) {
								continue;
							}
							$lesson_model = new Lesson([
								'teacher'          => $lesson_info['teacher'],
								'classroom'        => $lesson_info['classroom'],
								'name'             => $lesson_info['name'],
								'type'             => $lesson_info['type'],
								'is_odd'           => ($is_odd == '1'),
								'day'              => $day,
								'lesson_number_id' => $lesson_number,
								'group_id'         => $group->getKey('id'),
							]);

							$lesson_model->save();
						}
					}
				}
			}
		}

		$this->is_activate = true;
		$this->save();

	}
}
