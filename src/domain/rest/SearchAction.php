<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\helpers\ClientHelper;

/**
 * @property BaseActiveService $service
 */
class SearchAction extends IndexAction {

	public $fields = [];
	
	public function run() {
		$getParams = Yii::$app->request->get();
		$query = ClientHelper::getQueryFromRequest($getParams);
		$title = Yii::$app->request->post('title');
		if(empty($title) || mb_strlen($title) < 3) {
			return [];
		}
		$likeCondition = $this->generateLikeCondition($title);
		$query->andWhere($likeCondition);
		return $this->service->getDataProvider($query);
	}

	private function generateLikeCondition($title) {
		$q = Query::forge();
		foreach($this->fields as $key) {
			$q->orWhere(['ilike', $key, $title]);
		}
		return $q->getParam('where');
	}
}
