<?php

namespace App\Http\Sections;

use App\Proxy;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Display\ControlButton;
use SleepingOwl\Admin\Display\ControlLink;
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
			\AdminColumn::custom('Callback URL', function (Proxy $model) {
				return route('callback.proxy', ['name' => $model->name]);
			}),
		]);

		$ping = new ControlButton(function (Proxy $model) {
			return $model->getUrl();
		}, 'ping');
		$ping->setIcon('fa fa-signal');
		$ping->setHtmlAttribute('class', 'proxy-ping-btn');

		$show_log = new ControlLink(function (Proxy $model) {
			return route('admin.model', [
				'adminModel' => 'proxies_logs',
				'proxy_name' => $model->name
			]);
		}, 'logs');
		$show_log->setIcon('fa fa-list');

		$control = $display->getColumns()->getControlColumn();
		$control->addButtons([
			$show_log,
			$ping
		]);
		$control->setWidth("300px");

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
