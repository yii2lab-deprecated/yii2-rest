<?php

namespace yii2lab\core\domain\repositories\rest;

use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use yii2lab\domain\repositories\BaseApiRepository;

class ClientRepository extends BaseApiRepository {
	
	public function getBaseUrl() {
		$baseUrl = env('servers.core.domain');
		if(YII_ENV_TEST) {
			$baseUrl .= 'index-test.php/';
		}
		return trim($baseUrl, SL);
	}
	
	protected function showServerException($response) {
		$exception = $response->data['type'];
		if(YII_DEBUG) {
			throw new $exception($response->data['message']);
		}
		if($exception == 'yii2woop\common\virt\exceptions\ExternalException') {
			throw new ServerErrorHttpException(t('tps', 'ExternalException'));
		}
		parent::showServerException($response);
	}
	
	protected function showUserException($response) {
		$statusCode = $response->statusCode;
		if($statusCode == 422) {
			throw new UnprocessableEntityHttpException($response->data);
		}
		parent::showUserException($response);
	}
}
