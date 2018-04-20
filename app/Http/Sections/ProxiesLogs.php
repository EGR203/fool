<?php

namespace App\Http\Sections;

use App\ProxiesLog;
use App\Proxy;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Proxies
 *
 * @property \App\ProxiesLog $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class ProxiesLogs extends Section implements Initializable {
	/**
	 * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
	 *
	 * @var bool
	 */
	protected $checkAccess = false;

	/**
	 * @var string
	 */
	protected $title = "Proxies logs";

	/**
	 * @var \App\ProxiesLog
	 */
	protected $model = '\App\ProxiesLogs';

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
			\AdminColumn::custom('Proxy')->setCallback(function (ProxiesLog $log) {
				$link = new \SleepingOwl\Admin\Display\ControlLink(function () use ($log) {
					return route('admin.model', [
						'adminModel' => 'proxies_logs',
						'proxy_name' => $log->getProxy()->name,
					]);
				}, $log->getProxy()->name);
				$link->setModel($log);

				return $link->render();

			}),
			\AdminColumn::custom('Request')->setCallback(function (ProxiesLog $log) {
				return "<pre>" . htmlentities($log->request) . "</pre>";
			})->setWidth("200px"),
			\AdminColumn::custom('Response')->setCallback(function (ProxiesLog $log) {
				return "<pre style='overflow: scroll; max-width: 700px; max-height: 320px'>" . htmlentities($log->response) . "</pre>";
			})->setWidth(400),
			\AdminColumn::datetime('created_at', 'Created')->setWidth(150),
		])->paginate(25);


		$display->setFilters(
			\AdminDisplayFilter::custom('proxy_name')->setCallback(function ($query, $proxy_name) {
				if ($proxy = Proxy::where('name', $proxy_name)->first()) {
					$proxy_id = $proxy->id;
					$query->where('proxy_id', $proxy_id);
				}
			}),
			\AdminDisplayFilter::custom('proxy_id')->setCallback(function ($query, $proxy_id) {
				$query->where('proxy_id', $proxy_id);
			})

		);

		$display->setApply(function ($query) {
			$query->orderBy('id', 'desc');
		});

		return $display;
	}

	/**
	 * @return void
	 */
	public function onDelete($id) {
		// remove if unused
	}


	public function getIcon() {
		return 'fa fa-list';
	}
}
