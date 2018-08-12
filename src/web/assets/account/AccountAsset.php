<?php

namespace yii2lab\rest\web\assets\account;

use yii\web\AssetBundle;

class AccountAsset extends AssetBundle
{
    public $sourcePath = '@yii2lab/rest/web/assets/account/dist';
    public $js = [
        'js/domain.js',
        'js/services/auth.js',
        'js/services/token.js',
    ];
	public $depends = [
		//'yii2lab\rest\web\assets\rest\RestAsset',
        'yii2lab\rest\web\assets\storage\StorageAsset',
	];
}
