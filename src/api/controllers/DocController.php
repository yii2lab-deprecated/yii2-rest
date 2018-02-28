<?php

namespace yii2lab\rest\api\controllers;

use yii\rest\Controller;
use yii2lab\rest\domain\helpers\RouteHelper;

class DocController extends Controller
{
	
	public function actionIndex() {
        return RouteHelper::allRoutes();
    }

}
