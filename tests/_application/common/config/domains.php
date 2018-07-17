<?php

use yii\helpers\ArrayHelper;
use yii2lab\domain\enums\Driver;
use yii2lab\test\helpers\TestHelper;

$config = [
	'account' => 'yii2module\account\domain\v2\Domain',
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
