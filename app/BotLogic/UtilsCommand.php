<?php
namespace App\BotLogic;
use App\Group;
use App\LessonTime;
use App\Lesson;
use Carbon\Carbon;

trait UtilsCommand {

	protected function prepareAllLessonsForGroupsByDate($groups, Carbon $date = null, bool $strict) {
		$date = $date ? clone $date : Carbon::now();
		$groups_lessons = [];
		$is_empty = True;
		$parity_dict = [
			'0' => ' ПО ЧЕТНЫМ НЕДЕЛЯМ!',
			'1' => ' ПО НЕЧЕТНЫМ НЕДЕЛЯМ!'
		];
		foreach ($groups as $undergroup) {
			$ls =  $undergroup->lessonsByDate($date, $strict);
			$lessons = [];
			if ( count($ls) ) {
				$is_empty = false;
				// в $strict режиме нам приходит 1 лекция
				if ($strict) {
					foreach ($ls as $l) {
						$lessons[$l->lesson_number_id] = $l->toStr();
					}
				} else {
					$grouped = $ls->groupBy('lesson_number_id');
					foreach ($grouped as $l_num => $g_ls) {
						if(count($g_ls) == 1) {
							$lessons[$l_num] = $g_ls[0]->toStr() . $parity_dict[$g_ls[0]->is_odd];
						} else {
							$zero = $g_ls[0];
							$first = $g_ls[1];
							if ($zero->toStr() == $first->toStr()) {
								$lessons[$l_num] = $zero->toStr();
							} else {
								$lessons[$l_num] = $first->toStr() . $parity_dict[$first->is_odd] . " \\n";
								$lessons[$l_num] .= $zero->toStr() . $parity_dict[$zero->is_odd];
							}
						}
					}
					//todo нужно придумать как сделать чет/нечет
				}
			}
			$groups_lessons[] = $lessons;
		}
		if ($is_empty) {
			return $date->format('l') . " - нет пар";
		}
		$msg = $date->format('l') . ":\\n";
		foreach ($groups_lessons as $group_num => $lessons_info) {
			$msg .= "==========================\\n";
			$msg .= " Для ".($group_num + 1)." группы:\\n";
			$msg .= implode('\n-----------\n', $lessons_info);
			$msg .= "\\n\\n";
		}

		return $msg;
	}

	/**
	 * Ищет ближайшую лекцию.
	 *
	 * @param $groups колеция или массив групп
	 * @param Carbon|null $date дата для поиска, по умолчанию используется текущая дата
	 * @param bool $strict При строгом режиме, будет искать только среди сегоднешних лекций,
	 * иначе переключится на другой день и так до первой найденной лекции
	 *
	 * @return string
	 */
	protected function prepareNearestLessonForGroupsByDate($groups, Carbon $date = null, bool $strict = True) {
		$date = $date ? clone $date : Carbon::now();
		// При строгом и нестрогом режиме отличаются даты
		$old_date = clone $date;
		$lessons_info = [];
		$is_empty = True;
		foreach ($groups as $undergroup) {
			$lesson =  $undergroup->getNearestLessonByDate($date, $strict);
			if ( $lesson ) {
				$lessons_info[] = $lesson->toStr();
				$is_empty = false;
			}
		}
		if ($is_empty) {
			return $old_date->format('l') . " - нет пар";
		}

		if (count($lessons_info) > 1) {
			for ($i = count($lessons_info) -1 ; $i >= 1 ; $i--) {
				if($lessons_info[$i] == $lessons_info[$i-1]) {
					$lessons_info[$i] = 'Так же';
				}
			}
		}
		$msg = $date->format('l') . ':\n';
		foreach ($lessons_info as $i => $li) {
			$group_num = $i+1;
			$msg .= "Для $group_num группы: " . $li . '\n';
		}
		return $msg;
	}
}
