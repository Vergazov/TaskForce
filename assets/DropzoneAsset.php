<?php

namespace app\assets;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $basePath = '@webroot/vendor/dropzone';
    public $baseUrl = '@web/vendor/dropzone';
    public $css = [
        'basic.css',
        'dropzone.css',
    ];
    public $js = [
        'dropzone.js',
    ];

//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap5\BootstrapAsset'
//    ];
}
