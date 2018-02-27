<?php

namespace yii2lab\rest\domain\helpers;

use yii\web\ServerErrorHttpException;
use yii\httpclient\Client;
use yii2lab\rest\domain\entities\RequestEntity;
use yii\httpclient\Request;
use yii2lab\misc\enums\HttpMethodEnum;

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

    public static function sendRequest(RequestEntity $requestEntity) {
        return self::runRequest($requestEntity);
    }

    protected static function runRequest($data) {
        if($data instanceof RequestEntity) {
            $requestEntity = $data;
        } else {
            $requestEntity = new RequestEntity;
            $requestEntity->load($data);
        }
        $request = self::createHttpRequest($requestEntity);
        try {
            $response = $request->send();
        } catch(\yii\httpclient\Exception $e) {
            throw new ServerErrorHttpException('Url "' . $request->url . '" is not available');
        }
        return $response;
    }

    /**
     * @param RequestEntity $requestEntity
     * @throws
     *
     * @return Request
     */
    protected static function createHttpRequest(RequestEntity $requestEntity) {
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

}