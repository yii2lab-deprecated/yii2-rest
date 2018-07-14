<?php

namespace yii2lab\rest\domain\rest;

use yii2lab\helpers\ClientHelper;

class ViewActionWithQuery extends BaseAction {

	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$query = ClientHelper::getQueryFromRequest();
		return $this->runServiceMethod($id, $query);
	}

}
