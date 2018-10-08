<?php

namespace yii2lab\rest\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\enums\ApiDocEnum;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\postman\PostmanHelper;
use yii2lab\rest\domain\helpers\RouteHelper;
use yii2mod\helpers\ArrayHelper;

/**
 * Class MockController
 *
 * @package yii2lab\rest\api\controllers
 *
 * @property \yii2lab\rest\api\Module $module
 */
class MockController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'cors' => Behavior::cors(),
        ];
    }

	public function init() {
		if(!$this->module->isEnabledDoc) {
			throw new NotFoundHttpException('Documentation is disabled');
		}
		parent::init();
	}
	
	public function actionIndex($route) {
        $requestEntity = $this->forgeRequestEntity($route);
        return $this->forgeApiResponse($requestEntity);
    }

    private function forgeRequestEntity($route) {
        $requestEntity = new RequestEntity();
        $requestEntity->method = Yii::$app->request->method;
        $requestEntity->uri = $route;
        $requestEntity->headers = Yii::$app->request->headers;
        $requestEntity->data = Yii::$app->request->bodyParams;
        return $requestEntity;
    }

    private function forgeApiResponse(RequestEntity $requestEntity) {
        $mockEntity = \App::$domain->rest->mock->oneByRequest($requestEntity);
        if($mockEntity->response->headers) {
            foreach ($mockEntity->response->headers as $key => $value) {
                Yii::$app->response->headers->add($key, $value);
            }
        }
        Yii::$app->response->statusCode = $mockEntity->response->status_code;
        Yii::$app->response->format = $mockEntity->response->format;
        return $mockEntity->response->data;
    }

}
