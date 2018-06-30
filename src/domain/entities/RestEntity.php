<?php

namespace yii2lab\rest\domain\entities;

use yii2lab\domain\BaseEntity;

/**
 * Class RestEntity
 *
 * @package yii2lab\rest\domain\entities
 *
 * @property $id
 * @property $tag
 * @property $module_id
 * @property $request
 * @property $response
 * @property $method
 * @property $endpoint
 * @property $description
 * @property $status
 * @property $stored_at
 * @property $favorited_at
 */
class RestEntity extends BaseEntity {

	protected $id;
	protected $tag;
	protected $module_id;
	protected $request;
	protected $response;
	protected $method;
	protected $endpoint;
	protected $description;
	protected $status;
	protected $stored_at;
	protected $favorited_at;

}