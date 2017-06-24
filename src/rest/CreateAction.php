<?php

namespace yii2lab\rest\rest;

use Yii;
use yii\base\Model;
use yii\rest\Action;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class CreateAction extends Action
{
	public $scenario = Model::SCENARIO_DEFAULT;
	public $viewAction = 'view';
	public $formClass;

	public function run()
	{
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id);
		}

		if(!empty($this->formClass)) {
			$model = new $this->formClass();
		} else {
			$model = new $this->modelClass();
		}
		$model->scenario = $this->scenario;
		
		$response = Yii::$app->getResponse();
		$body = Yii::$app->getRequest()->getBodyParams();
		$model->load($body, '');
		
		if ($entity = $model->save()) {
			$response->setStatusCode(201);
			if(method_exists($model, 'getPrimaryKey')) {
				$id = implode(',', array_values($model->getPrimaryKey(true)));
				$response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
			}
			if(is_object($entity)) {
				$model = $entity;
			}
		} elseif ($model->hasErrors()) {
			$response->setStatusCode(422);
			return $model;
		} elseif (!$model->hasErrors()) {
			throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
		}

		return $model;
	}
}
