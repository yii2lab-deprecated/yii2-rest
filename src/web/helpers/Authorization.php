<?php

namespace yii2lab\rest\web\helpers;

use Yii;
use yii2lab\helpers\yii\ArrayHelper;
use yii2lab\rest\domain\helpers\AuthorizationHelper;
use yii2module\account\domain\v2\entities\LoginEntity;

class Authorization
{

	public static $password = 'Wwwqqq111';
	
    static public function loginListForSelect() {
		$loginList = Yii::$app->account->test->all();
	    $loginListForSelect = [];
	    if(!empty($loginList)) {
            foreach($loginList as $login) {
                $loginListForSelect[$login->login] = $login->login . ' - ' . $login->username;
            }
        }
        $loginListForSelect = ArrayHelper::merge(['' => 'Guest'], $loginListForSelect);
        return $loginListForSelect;
    }

    static public function getTokenByLogin($login)
    {
	    /** @var LoginEntity $userEntity */
	    $userEntity = $loginList = Yii::$app->account->test->oneByLogin($login);
	    $password = !empty($userEntity->password) ?  $userEntity->password: self::$password;
	    
	    $baseUrl = rtrim(Yii::$app->controller->module->baseUrl, '/') . '/';
	    $token = AuthorizationHelper::getToken($baseUrl . 'auth', $userEntity->login, $password);
	    
		if(!empty($token)) {
			Token::save($login, $token);
		}
        return $token;
    }

    static public function sendRequest($model)
    {
        if(empty($model->authorization)) {
            $record = Request::send($model);
            return $record;
        }
        $token = Token::load($model->authorization);
        if(empty($token)) {
            $token = Authorization::getTokenByLogin($model->authorization);
        }
        $record = self::putTokenInModel($model, $token);

        if($record->status == 401) {
            $token = Authorization::getTokenByLogin($model->authorization);
            $record = self::putTokenInModel($model, $token);
        }

        return $record;
    }

    static public function putTokenInModel($model, $token)
    {
        $modelAuth = clone $model;
		if(!empty($token)) {
            $modelAuth->headerKeys[] = 'Authorization';
            $modelAuth->headerValues[] = $token;
            $modelAuth->headerActives[] = 1;
        }
        $record = Request::send($modelAuth);
        return $record;
    }

}