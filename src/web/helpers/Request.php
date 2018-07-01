<?php

namespace yii2lab\rest\web\helpers;

use Yii;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\web\models\ResponseRecord;
use yii2lab\rest\web\models\RequestForm;

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
        /** @var \yii\httpclient\Client $client */
        $client = Yii::createObject(Yii::$app->controller->module->clientConfig);
        $client->baseUrl = Yii::$app->controller->module->baseUrl;
        try {
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

    static public function createRequestFrom($endpoint, $method = 'get', $body = [], $header = [])
    {
        $modelAuth = Yii::createObject(RequestForm::class);
        $modelAuth->method = $method;
        $modelAuth->endpoint = $endpoint;
        if(!empty($body)) {
            foreach($body as $bodyKey => $bodyValue) {
                $modelAuth->bodyKeys[] = $bodyKey;
                $modelAuth->bodyValues[] = $bodyValue;
                $modelAuth->bodyActives[] = 1;
            }
        }
        if(!empty($header)) {
            foreach($header as $headerKey => $headerValue) {
                $modelAuth->headerKeys[] = $headerKey;
                $modelAuth->headerValues[] = $headerValue;
                $modelAuth->headerActives[] = 1;
            }
        }
        return $modelAuth;
    }

    static protected function createResponseRecord($response)
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