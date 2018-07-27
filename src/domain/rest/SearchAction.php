<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\helpers\ClientHelper;

/**
 * @property BaseActiveService $service
 *
 * @deprecated
 */
class SearchAction extends IndexAction {

	public $fields = [];
	
	public function run() {
		$getParams = Yii::$app->request->get();
		$query = ClientHelper::getQueryFromRequest($getParams);
		$text = Yii::$app->request->post('title');
		$query->where(BaseActiveService::SEARCH_PARAM_NAME, $text);
		return $this->service->getDataProvider($query);
	}

}
