<?php

namespace App\Http\Sections;

use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Proxies
 *
 * @property \App\Proxy $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Proxies extends Section implements Initializable {
	/**
	 * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
	 *
	 * @var bool
	 */
	protected $checkAccess = false;

	/**
	 * @var string
	 */
	protected $title = "Proxies";

	/**
	 * @var \App\Proxy
	 */
	protected $model = '\App\Proxy';

	/**
	 * @var array
	 */
	protected $redirect = ['edit' => 'display', 'create' => 'display'];

	/**
	 * @var string
	 */
	protected $alias;

	public function initialize() {

		$this->addToNavigation();

	}

	/**
	 * @return DisplayInterface
	 */
	public function onDisplay() {
		$display = \AdminDisplay::table()->setColumns([
			\AdminColumn::link('name')->setLabel('Name')->setWidth('400px'),
			\AdminColumnEditable::text('ip', 'Ip'),
			\AdminColumnEditable::text('path', 'Path'),
		]);
		$display->paginate(40);

		return $display;
	}

	/**
	 * @param int $id
	 *
	 * @return FormInterface
	 */
	public function onEdit($id) {
		$form = \AdminForm::panel()->addBody(
			\AdminFormElement::text('name', 'Name')->required()->unique(),
			\AdminFormElement::text('ip', 'Ip')->required(),
			\AdminFormElement::text('path', 'Path')->required()
		);

		return $form;
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
		// remove if unused
	}

	/**
	 * @return void
	 */
	public function onRestore($id) {
		// remove if unused
	}

	public function getIcon() {
		return 'fa fa-exchange';
	}
}
