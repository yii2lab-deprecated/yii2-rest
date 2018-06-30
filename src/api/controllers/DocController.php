<?php

namespace yii2lab\rest\api\controllers;

use yii\rest\Controller;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;
use yii2lab\rest\domain\helpers\RouteHelper;

class DocController extends Controller
{
	
	public function actionIndex() {
        return RouteHelper::allRoutes();
    }
	
	public function actionPostman($version) {
		$apiVersion = MiscHelper::currentApiVersion();
		return PostmanHelper::generate($apiVersion, $version);
	}

}
