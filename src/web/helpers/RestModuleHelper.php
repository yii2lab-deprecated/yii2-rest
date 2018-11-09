<?php

namespace yii2lab\rest\web\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rest\domain\enums\RestPermissionEnum;
use yii2lab\rest\domain\helpers\MiscHelper;

class RestModuleHelper {
	
	public static function getConfig() {
		$config = [];
		$apiVersionList = MiscHelper::getAllVersions();
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