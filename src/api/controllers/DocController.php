<?php

namespace yii2lab\rest\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;
use yii2lab\rest\domain\helpers\RouteHelper;

/**
 * Class DocController
 *
 * @package yii2lab\rest\api\controllers
 *
 * @property \yii2lab\rest\api\Module $module
 */
class DocController extends Controller
{
	
	public function init() {
		if(!$this->module->isEnabledDoc) {
			throw new NotFoundHttpException('Documentation is disabled');
		}
		parent::init();
	}
	
	public function actionIndex() {
        return RouteHelper::allRoutes();
    }
	
	public function actionHtml() {
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
