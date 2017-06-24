<?php

namespace yii2lab\rest\rest;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\ForbiddenHttpException;

class ActiveController extends Controller
{
	public $modelClass;
	public $formClass;
	public $updateScenario = Model::SCENARIO_DEFAULT;
	public $createScenario = Model::SCENARIO_DEFAULT;
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		if ($this->modelClass === null) {
			throw new InvalidConfigException('The "modelClass" property must be set.');
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'index' => [
				'class' => 'yii\rest\IndexAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'view' => [
				'class' => 'yii\rest\ViewAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'create' => [
				'class' => 'yii2lab\rest\rest\CreateAction',
			'modelClass' => $this->modelClass,
			'checkAccess' => [$this, 'checkAccess'],
			'scenario' => $this->createScenario,
			'formClass' => $this->formClass,
			],
			'update' => [
				'class' => 'yii2lab\rest\rest\UpdateAction',
			'modelClass' => $this->modelClass,
			'checkAccess' => [$this, 'checkAccess'],
			'scenario' => $this->updateScenario,
			'formClass' => $this->formClass,
			],
			'delete' => [
				'class' => 'yii\rest\DeleteAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'options' => [
				'class' => 'yii\rest\OptionsAction',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'index' => ['GET', 'HEAD'],
			'view' => ['GET', 'HEAD'],
			'create' => ['POST'],
			'update' => ['PUT', 'PATCH'],
			'delete' => ['DELETE'],
		];
	}

	/**
	 * Checks the privilege of the current user.
	 *
	 * This method should be overridden to check whether the current user has the privilege
	 * to run the specified action against the specified data model.
	 * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
	 *
	 * @param string $action the ID of the action to be executed
	 * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
	 * @param array $params additional parameters
	 * @throws ForbiddenHttpException if the user does not have access
	 */
	public function checkAccess($action, $model = null, $params = [])
	{
	}
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		if(!method_exists($this, 'getAccessRules')) {
			return $behaviors;
		}
		$access = $this->getAccessRules();
		if(!empty($access)) {
			$behaviors['access'] = $access;
			$behaviors['authenticator'] = [
				'class' => 'common\filters\auth\HttpTpsAuth',
			];
		}
		return $behaviors;
	}
}