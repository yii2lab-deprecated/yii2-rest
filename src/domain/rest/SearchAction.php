<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\helpers\ClientHelper;

/**
 * Class ServiceController
 *
 * @package yii2woop\service\api\v3\controllers
 *
 * @property BaseActiveService $service
 */
class SearchAction extends IndexAction {

	public $fields = [];
	
	public function run() {
		$getParams = Yii::$app->request->get();
		$query = ClientHelper::getQueryFromRequest($getParams);
		foreach($this->fields as $key) {
			$param = Yii::$app->request->post($key);
			if(!empty($param)) {
				$query->andWhere(['ilike', $key, $param]);
			}
		}
		return $this->service->getDataProvider($query);
	}

}
