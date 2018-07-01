<?php

namespace yii2lab\rest\web\helpers;

use Yii;
use yii\httpclient\Exception;
use yii\httpclient\Response;
use yii\web\NotFoundHttpException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\web\models\ResponseRecord;

class Request
{

    static public function send(RequestEntity $model)
    {
        $begin = microtime(true);
        $response = self::httpRequest($model);
        $duration = microtime(true) - $begin;

        $record = self::createResponseRecord($response);
        $record->duration = $duration;
        return $record;
    }

    static public function httpRequest(RequestEntity $model)
    {
	    /** @var \yii2lab\rest\web\Module $module */
	    $module = Yii::$app->controller->module;
        /** @var \yii\httpclient\Client $client */
        $client = Yii::createObject($module->clientConfig);
        $client->baseUrl = $module->baseUrl;
        try {
	        /** @var Response $response */
	        $response = $client->createRequest()
				->setMethod($model->method)
				->setUrl($model->uri)
				->setData($model->data)
				->setHeaders($model->headers)
				->send();
		} catch(Exception $e) {
        	if($e->getCode() == 2) {
        		$message = 'Possible reasons:
						<ul>
							<li>You have an incorrect domain. See the link: <a href="' . $client->baseUrl . '" target="_blank">' . $client->baseUrl . '</a></li>
						</ul>';
		        throw new NotFoundHttpException($message);
	        }
		}
        return $response;
    }

    private static function createResponseRecord(Response $response)
    {
        $record = new ResponseRecord();
        $record->status = $response->getStatusCode();
        foreach ($response->getHeaders() as $name => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
            $record->headers[$name] = $values;
        }
        $record->content = $response->getContent();
        return $record;
    }

}