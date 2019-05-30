<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\traits\ActionEventTrait;

class ActiveControllerWithQuery extends Controller {
	
	use ActionEventTrait;
	
	public function actions() {
		return [
			'index' => [
				'class' => IndexActionWithQuery::class,
				'serviceMethod' => 'getDataProvider',
			],
			'create' => [
				'class' => CreateAction::class,
			],
			'view' => [
				'class' => ViewActionWithQuery::class,
			],
			'search' => [
				'class' => SearchAction::class,
			],
			'update' => [
				'class' => UpdateAction::class,
				'serviceMethod' => 'updateById',
			],
			'delete' => [
				'class' => DeleteAction::class,
				'serviceMethod' => 'deleteById',
			],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
		];
	}
	
	protected function verbs() {
		return [
			'index' => ['GET', 'HEAD'],
			'view' => ['GET', 'HEAD'],
			'create' => ['POST'],
			'update' => ['PUT', 'PATCH'],
			'delete' => ['DELETE'],
			'options' => ['OPTIONS'],
			'search' => ['POST'],
		];
	}
	
	public function actionOptions() {
		if(Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
			Yii::$app->getResponse()->setStatusCode(405);
		}
		//Yii::$app->getResponse()->getHeaders()->set('Allow',['DELETE']);
	}
}
