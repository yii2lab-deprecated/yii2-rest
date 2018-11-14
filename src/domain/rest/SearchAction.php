<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii\web\BadRequestHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\extension\activeRecord\helpers\SearchHelper;
use yii2lab\extension\web\helpers\ClientHelper;

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
		$query->where(SearchHelper::SEARCH_PARAM_NAME, $text);
		try {
			return $this->service->getDataProvider($query);
		} catch(BadRequestHttpException $e) {
			$error = new ErrorCollection;
			$error->add('title', $e->getMessage());
			throw new UnprocessableEntityHttpException($error);
		}
	}

}
