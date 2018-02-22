<?php

namespace yii2lab\rest\web\helpers;

use Yii;
use yii2lab\test\models\Login;

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

    static public function getToken($login)
    {
	    $user = $loginList = Yii::$app->account->test->oneByLogin($login);
	    $password = !empty($user->password) ?  $user->password: self::$password;
	    
    	$modelAuth = Request::createRequestFrom('auth', 'post', [
            'login' => $login,
            'password' => $password,
        ]);
	    
        $response = Request::httpRequest($modelAuth);
		if(empty($response)) {
            return null;
        }
        if($response->statusCode != 200) {
            return null;
        }
        $bodyAuth = json_decode($response->content);
        if(empty($bodyAuth) || empty($bodyAuth->token)) {
            return null;
        }
        Token::save($login, $bodyAuth->token);
        return $bodyAuth->token;
    }

    static public function sendRequest($model)
    {
        if(empty($model->authorization)) {
            $record = Request::send($model);
            return $record;
        }

        $token = Token::load($model->authorization);
        if(empty($token)) {
            $token = Authorization::getToken($model->authorization);
        }
        $record = self::putTokenInModel($model, $token);

        if($record->status == 401) {
            $token = Authorization::getToken($model->authorization);
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