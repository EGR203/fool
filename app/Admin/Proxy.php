<?php

use \App\Proxy;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Proxy::class, function (ModelConfiguration $model) {
	$model->setTitle('Proxies');
	$model->onDisplay(function () {
		$display = AdminDisplay::table()->setColumns([
			AdminColumn::link('name')->setLabel('Name')->setWidth('400px'),
			AdminColumn::text('ip', 'Ip'),
			AdminColumn::text('path', 'Path'),
		]);
		$display->paginate(40);

		return $display;
	});
	// Create And Edit
	$model->onCreateAndEdit(function () {
		$form = AdminForm::panel()->addBody(
			AdminFormElement::text('name', 'Name')->required()->unique(),
			AdminFormElement::text('ip', 'Ip')->required(),
			AdminFormElement::text('path', 'Path')->required()
		);

		return $form;
	});
	$model->setRedirect(['create' => 'display', 'edit' => 'display']);

})->addMenuPage(Proxy::class, 0)->setIcon('fa fa-exchange');
