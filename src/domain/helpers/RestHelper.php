<?php

namespace yii2lab\rest\domain\helpers;

use yii\httpclient\Request;
use yii\httpclient\Response;
use yii\web\ServerErrorHttpException;
use yii\httpclient\Client;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\ResponseEntity;

class RestHelper {

    public static function get($uri, array $data = [], array $headers = [], array $options = []) {
        $method = HttpMethodEnum::GET;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function post($uri, array $data = [], array $headers = [], array $options = []) {
        $method = HttpMethodEnum::POST;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function put($uri, array $data = [], array $headers = [], array $options = []) {
        $method = HttpMethodEnum::PUT;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function del($uri, array $data = [], array $headers = [], array $options = []) {
        $method = HttpMethodEnum::DELETE;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    /**
     * @param RequestEntity $requestEntity
     * @throws
     *
     * @return ResponseEntity
     */
    public static function sendRequest(RequestEntity $requestEntity) {
	    /** @var Request $request */
	    $request = self::buildRequestClass($requestEntity);
	    $begin = microtime(true);
        try {
	        $response = $request->send();
        } catch(\yii\httpclient\Exception $e) {
            throw new ServerErrorHttpException('Url "' . $request->url . '" is not available');
        }
	    $end = microtime(true);
	    $duration = $end - $begin;
        return self::buildResponseEntity($response, $duration);
    }

    private static function runRequest(array $data) {
        $requestEntity = new RequestEntity;
        $requestEntity->load($data);
        return self::sendRequest($requestEntity);
    }

    /**
     * @param RequestEntity $requestEntity
     * @return Request
     * @throws
     */
    private static function buildRequestClass(RequestEntity $requestEntity) {
        $requestEntity->validate();
        $httpClient = new Client();
        $request = $httpClient->createRequest();
        $request
            ->setOptions($requestEntity->options)
            ->setMethod($requestEntity->method)
            ->setUrl($requestEntity->uri)
            ->setData($requestEntity->data)
            ->setHeaders($requestEntity->headers)
	        ->setCookies($requestEntity->cookies)
            ->addHeaders(['user-agent' => 'Awesome-Octocat-App']);
        return $request;
    }
	
	/**
	 * @param Response $response
	 * @param          $duration
	 *
	 * @return ResponseEntity
	 */
    private static function buildResponseEntity(Response $response, $duration) {
        $headers = [];
        foreach($response->headers as $k => $v) {
        	$headers[strtolower($k)] = $v[0];
        }
    	$responseEntity = new ResponseEntity;
        $responseEntity->data = $response->data;
        $responseEntity->headers = $headers;
        $responseEntity->content = $response->content;
        $responseEntity->format = $response->format;
        $responseEntity->cookies = $response->cookies;
        $responseEntity->status_code = $response->statusCode;
	    $responseEntity->duration = $duration;
        return $responseEntity;
    }

}