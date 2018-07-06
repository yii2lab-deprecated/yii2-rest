<?php

namespace yii2lab\rest\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;
use yii2lab\rest\domain\helpers\RouteHelper;

class DocController extends Controller
{
	
	public function actionIndex() {
        return RouteHelper::allRoutes();
    }
	
	public function actionHtml() {
		//prr('actionHtml',1,1);
		Yii::$app->response->format = Response::FORMAT_HTML;
		$content = FileHelper::load(API_DIR . DS . API_VERSION_STRING . DS . 'docs' . DS . 'dist' . DS . 'index.html');
		return $content;
	}
 
	public function actionPostman($version) {
		$apiVersion = MiscHelper::currentApiVersion();
		return PostmanHelper::generate($apiVersion, $version);
	}
	
	public function actionNormalizeCollection() {
		Yii::$domain->rest->rest->normalizeTag();
	}
	
	public function actionExportCollection() {
		Yii::$domain->rest->rest->exportCollection();
	}
	
	public function actionImportCollection() {
		Yii::$domain->rest->rest->importCollection();
	}
}
