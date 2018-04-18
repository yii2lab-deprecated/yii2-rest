<?php

namespace yii2lab\rest\domain\repositories\base;

use yii2lab\domain\repositories\BaseRepository;
use yii2lab\rest\domain\traits\RestTrait;

abstract class BaseRestRepository extends BaseRepository {

	use RestTrait;
	
}
