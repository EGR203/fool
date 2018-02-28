<?php

namespace App\Http\Sections;

use App\Schedule;
use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Display\ControlButton;
use SleepingOwl\Admin\Section;

/**
 * Class Schedules
 *
 * @property \App\Schedule $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Schedules extends Section implements Initializable {
	/**
	 * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
	 *
	 * @var bool
	 */
	protected $checkAccess = false;

	/**
	 * @var \App\Schedule
	 */
	protected $model = '\App\Schedule';

	/**
	 * @var string
	 */
	protected $title = "Schedules";

	protected $redirect = ['edit' => 'display', 'create' => 'display'];

	/**
	 * @var string
	 */
	protected $alias = "schedules";

	public function initialize() {

		$this->addToNavigation();

	}


	/**
	 * @return DisplayInterface
	 */
	public function onDisplay() {
		$display = \AdminDisplay::table()->setColumns(
			\AdminColumn::text("id", "#")->setWidth("30px"),
			\AdminColumnEditable::text("label", "Label"),
			\AdminColumn::text('created_at')->setLabel('Создано')
		)->paginate(20);

		$display->setFilters(
			\AdminDisplayFilter::field('is_activate')->setTitle('Is activate [:value]')
		);

		$control  = $display->getColumns()->getControlColumn();
		$download = new ControlButton(function (Model $model) {
			return url('/') . "/" . $model->file;
		}, "download", 10);
		$download->setIcon("fa fa-download");
		$control->addButton($download);

		$activate = new ControlButton(function (Model $model) {
			return route('admin.schedule.activate', ['id' => $model->id]);
		}, "activate", 0);

		$activate->setCondition(function (Model $model) {
			return !$model->is_activate;
		});

		$activate->setMethod('get');
		$activate->setIcon("fa fa-plus-square");
		$control->addButton($activate);
		$control->setWidth("400px");


		return $display;
	}

	/**
	 * @param int $id
	 *
	 * @return FormInterface
	 */
	public function onEdit($id) {
		// remove if unused

		$upload = \AdminFormElement::file('file', 'Table')->required();
		$upload->setView(view('admin.form.element.file'));

		return \AdminForm::panel([
			\AdminFormElement::text('id', 'ID')->setReadonly(1),
			\AdminFormElement::text('label', 'Label')->required(),
			$upload,
			\AdminFormElement::text('created_at')->setLabel('Создано')->setReadonly(1),
		]);

	}

	/**
	 * @return FormInterface
	 */
	public function onCreate() {
		return $this->onEdit(null);
	}

	/**
	 * @return void
	 */
	public function onDelete($id) {
		$schedule = Schedule::find($id);
		@unlink(base_path('public') . DIRECTORY_SEPARATOR . $schedule->file);
	}

	/**
	 * @return void
	 */
	public function onRestore($id) {
		// remove if unused
	}

	public function getIcon() {
		return 'fa fa-table';
	}

}
