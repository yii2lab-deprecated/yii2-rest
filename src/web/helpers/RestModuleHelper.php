<?php

namespace yii2lab\rest\web\helpers;

use common\enums\app\ApiVersionEnum;
use common\enums\rbac\PermissionEnum;
use yii2lab\domain\enums\Driver;
use yii2lab\helpers\Behavior;

class RestModuleHelper {
	
	public static function getConfig() {
		$config = [];
		$apiVersionList = ApiVersionEnum::values();
		foreach($apiVersionList as $version) {
			$config[ 'rest-' . $version ] = [
				'class' => 'yii2lab\rest\web\Module',
				'baseUrl' => env('url.api') . $version,
				'as access' => Behavior::access(PermissionEnum::REST_CLIENT_ALL),
				'storage' => Driver::primary() == Driver::FILEDB ? 'yii2lab\rest\web\storages\FiledbStorage' : 'yii2lab\rest\web\storages\DbStorage',
			];
		}
		return $config;
	}
	
}