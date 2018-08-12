<?php

namespace yii2lab\rest\web\assets\storage;

use yii\web\AssetBundle;

class StorageAsset extends AssetBundle
{
    public $sourcePath = '@yii2lab/rest/web/assets/storage/dist';
    public $js = [
        'js/domain.js',
        'js/services/local.js',
    ];
}
