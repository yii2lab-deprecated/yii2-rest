<?php

namespace yii2lab\rest\rest;

use yii\rest\Controller as YiiController;

class Controller extends YiiController
{
	public $fieldType = [];
	
	public function init()
	{
		$this->serializer = [
			'class' => 'yii2lab\rest\rest\Serializer'
		];
		if(!empty($this->fieldType)) {
			$this->serializer['fieldType'] = $this->fieldType;
		}
		parent::init();
	}
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$access = $this->getAccessRules();
		if(!empty($access)) {
			$behaviors['access'] = $access;
			$behaviors['authenticator'] = [
				'class' => 'common\filters\auth\HttpTpsAuth',
			];
		}
		return $behaviors;
	}
	
	protected function getAccessRules() {
		if (!method_exists($this, 'accessRules')) {
			return false;
		}
		$accessRules = $this->accessRules();
		if (empty($accessRules)) {
			return false;
		}
		$access = [
			'class' => 'yii\filters\AccessControl',
			'rules' => $accessRules,
		];
		return $access;
	}
}
