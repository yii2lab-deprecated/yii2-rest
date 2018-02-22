<?php

namespace yii2lab\rest\web;

use yii\web\AssetBundle;

/**
 * Class RestAsset
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RestAsset extends AssetBundle
{
    public $sourcePath = '@woop/module/rest/assets';
    public $css = [
        'main.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}