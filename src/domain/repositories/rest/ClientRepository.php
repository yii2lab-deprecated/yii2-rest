<?php

namespace yii2lab\rest\domain\repositories\rest;

use yii2lab\domain\repositories\BaseRepository;
use yii\web\ServerErrorHttpException;
use yii\httpclient\Client;
use yii2lab\rest\domain\entities\RequestEntity;
use yii\httpclient\Response;
use yii\httpclient\Request;

class ClientRepository extends BaseRepository {

    /**
     * @param RequestEntity $requestEntity
     * @throws
     *
     * @return Response
     */
    public function send(RequestEntity $requestEntity) {
        $request = $this->createHttpRequest($requestEntity);
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
    protected function createHttpRequest(RequestEntity $requestEntity) {
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
