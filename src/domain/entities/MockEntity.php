<?php

namespace yii2lab\rest\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\extension\web\enums\HttpMethodEnum;

/**
 * Class RequestEntity
 * @package yii2lab\domain\entities
 *
 * @property RequestEntity $request
 * @property ResponseEntity $response
 */
class MockEntity extends BaseEntity {

	protected $request;
    protected $response;

    public function fieldType() {
        return [
            'request' => [
                'type' => RequestEntity::class,
            ],
            'response' => [
                'type' => ResponseEntity::class,
            ],
        ];
    }
}