<?php

namespace yii2lab\rest\domain\rest;

use yii2lab\helpers\ClientHelper;

class IndexActionWithQuery extends BaseAction {

	public $serviceMethod = 'getDataProvider';
	
	public function run() {
		$query = ClientHelper::getQueryFromRequest();
		return $this->runServiceMethod($query);
	}

}
