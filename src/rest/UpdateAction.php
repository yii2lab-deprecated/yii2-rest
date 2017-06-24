<?php

namespace yii2lab\rest\rest;

use Yii;
use yii\base\Model;
use yii\rest\Action;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class UpdateAction extends Action
{
	public $scenario = Model::SCENARIO_DEFAULT;
	public $viewAction = 'view';
	public $formClass;

	public function run($id)
	{
		$entity = $this->findModel($id);
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id, $entity);
		}

		if(!empty($this->formClass)) {
			$model = new $this->formClass();
		} elseif(!empty($this->modelClass)) {
			$model = $entity;
		}
		$model->scenario = $this->scenario;

		$response = Yii::$app->getResponse();
		$body = Yii::$app->getRequest()->getBodyParams();
		$model->load($body, '');

		if ($entity = $model->save($id)) {
			$response->setStatusCode(200);
		} elseif ($model->hasErrors()) {
			$response->setStatusCode(422);
			return $model;
		} elseif (!$model->hasErrors()) {
			throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
		}

		return $model;
	}
}
