<?php

namespace yii2lab\rest\domain\helpers\postman;

use Yii;
use yii2lab\extension\web\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2module\account\domain\v2\entities\TestEntity;

class AuthorizationHelper {
	
	public static function genAuthCollection() {
		$items = [];
		/** @var TestEntity[] $loginList */
		$loginList = \App::$domain->account->test->all();
		foreach($loginList as $testEntity) {
			$requestEntity = self::genAuthRequestEntity($testEntity);
			$request = GeneratorHelper::genRequest($requestEntity);
			$items[] = [
				'name' => $requestEntity->uri . ($request['description'] ? " ({$request['description']})" : ''),
				'event' => GeneratorHelper::genEvent(null, self::genAuthScript()),
				'request' => $request,
				'response' => [],
			];
		}
		return $items;
	}
	
	private static function genAuthRequestEntity(TestEntity $testEntity) {
		$requestEntity = new RequestEntity();
		$requestEntity->uri = 'auth';
		$requestEntity->method = HttpMethodEnum::POST;
		$requestEntity->data = [
			'login' => $testEntity->login,
			'password' => 'Wwwqqq111',
		];
		$requestEntity->description = "by {$testEntity->login}";
		return $requestEntity;
	}
	
	private static function genAuthScript() {
		$variableName = GeneratorHelper::genPureVariable('token');
		$code = '
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

var authData = JSON.parse(responseBody);
pm.globals.set("' . $variableName . '", authData.token);';
		return $code;
	}
}