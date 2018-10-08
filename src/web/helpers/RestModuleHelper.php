<?php

namespace yii2lab\rest\web\helpers;

use common\enums\app\ApiVersionEnum;
use yii\helpers\ArrayHelper;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rest\domain\enums\RestPermissionEnum;

class RestModuleHelper {
	
	public static function getConfig() {
		$config = [];
		$apiVersionList = ApiVersionEnum::values();
		foreach($apiVersionList as $version) {
			$config[ 'rest-' . $version ] = [
				'class' => 'yii2lab\rest\web\Module',
				'baseUrl' => EnvService::getUrl('api', $version),
				'as access' => Behavior::access(RestPermissionEnum::CLIENT_ALL),
			];
		}
		return $config;
	}
	
	public static function appendConfig($config) {
		$restClientConfig = RestModuleHelper::getConfig();
		$config = ArrayHelper::merge($config, $restClientConfig);
		return $config;
	}
	
}