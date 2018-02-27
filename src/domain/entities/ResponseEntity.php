<?php

namespace yii2lab\rest\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class ResponseEntity
 * @package yii2lab\rest\domain\entities
 *
 * @property array $content
 * @property array $data
 * @property array $headers
 * @property array $cookies
 * @property integer $status_code
 * @property string $format
 */
class ResponseEntity extends BaseEntity {

    protected $content = [];
	protected $data = [];
	protected $headers = [];
    protected $cookies = [];
    protected $status_code = 200;
    protected $format;

    public function getIsOk() {
        return strncmp('20', $this->status_code, 2) === 0;
    }

    public function fieldType() {
        return [
            'status_code' => 'integer',
        ];
    }
}