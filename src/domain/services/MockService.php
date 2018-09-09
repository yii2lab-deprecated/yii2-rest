<?php

namespace yii2lab\rest\domain\services;

use yii2lab\domain\data\Query;
use yii2lab\domain\services\base\BaseActiveService;
use yii2lab\extension\encrypt\enums\HashAlgoEnum;
use yii2lab\extension\code\helpers\generator\EnumGeneratorHelper;
use yii2lab\extension\yii\helpers\FileHelper;
use yii2lab\rest\domain\entities\MockEntity;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\extension\store\StoreFile;
use yii2mod\helpers\ArrayHelper;

class MockService extends BaseActiveService {

    /**
     * @param RequestEntity $requestEntity
     * @return MockEntity
     */
	public function oneByRequest(RequestEntity $requestEntity) {
        $mockEntity = new MockEntity();
        $mockEntity->request = $requestEntity;
        $mockEntity = $this->touchFile($mockEntity);
        return $mockEntity;
	}

	private function touchFile(MockEntity $mockEntity) {
        $name = $this->generatePrefix($mockEntity->request);
        $file = COMMON_DATA_DIR . DS . 'rest' . DS . 'mock' . DS . $name . '.php';
        $store = new StoreFile($file);
        $mockData = $store->load();
        $isHas = !empty($mockData);
        if(!$isHas) {
            $mockData = $this->getDefaultMockData($mockEntity->request);
        }
        $mockEntity = new MockEntity($mockData);
        if(! $isHas && YII_ENV_DEV) {
            $store->save($mockEntity->toArray());
        }
        return $mockEntity;
    }

    private function getDefaultMockData(RequestEntity $requestEntity) {
        $mockData['request'] = $requestEntity->toArray();
        $mockData['response'] = [
            'content' => '',
            'data' => [
                "name" => "Not Found",
                "message" => "Была создана страница, она нуждается в ваших правках. " . PHP_EOL . "Место: @common/data/rest/mock/$name.php",
                "code" => 0,
                "status" => 404,
                "type" => "yii\\web\\NotFoundHttpException"
            ],
            'status_code' => 404,
            'format' => 'json',
            'duration' => 1,
        ];
        return $mockData;
    }

    private function generatePrefix(RequestEntity $requestEntity) {
        $segments = explode(SL, $requestEntity->uri);
        $prefix = implode(DS, $segments);
        $prefix .= DS . strtolower($requestEntity->method);
        $prefix .= DS . $this->generateHash($requestEntity);
        return $prefix;
    }

	private function generateHash(RequestEntity $requestEntity) {
        $data = [
            $requestEntity->method,
            $requestEntity->uri,
            $requestEntity->data,
        ];
        $data = ArrayHelper::sortRecursive($data);
        $scope = serialize($data);
        $hash = hash(HashAlgoEnum::CRC32B, $scope);
        return $hash;
    }
}
