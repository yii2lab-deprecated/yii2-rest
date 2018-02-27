<?php

namespace yii2lab\rest\domain\helpers;

use yii\web\ServerErrorHttpException;
use yii\httpclient\Client;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\ResponseEntity;

class RestHelper {

    public static function get($uri, $data = [], $headers = [], $options = []) {
        $method = HttpMethodEnum::GET;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function post($uri, $data = [], $headers = [], $options = []) {
        $method = HttpMethodEnum::POST;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function put($uri, $data = [], $headers = [], $options = []) {
        $method = HttpMethodEnum::PUT;
        return self::runRequest(compact('uri', 'data', 'headers', 'options', 'method'));
    }

    public static function delete($uri, $data = [], $headers = [], $options = []) {
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
        $request = self::buildRequestClass($requestEntity);
        try {
            $response = $request->send();
        } catch(\yii\httpclient\Exception $e) {
            throw new ServerErrorHttpException('Url "' . $request->url . '" is not available');
        }
        return self::buildResponseEntity($response);
    }

    private static function runRequest($data) {
        $requestEntity = new RequestEntity;
        $requestEntity->load($data);
        return self::sendRequest($requestEntity);
    }

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
            ->addHeaders(['user-agent' => 'Awesome-Octocat-App']);
        return $request;
    }

    private static function buildResponseEntity($response) {
        $responseEntity = new ResponseEntity;
        $responseEntity->data = $response->data;
        $responseEntity->headers = $response->headers;
        $responseEntity->content = $response->content;
        $responseEntity->format = $response->format;
        $responseEntity->cookies = $response->cookies;
        $responseEntity->status_code = $response->statusCode;
        return $responseEntity;
    }

}