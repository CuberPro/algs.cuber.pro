<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Yunqi Ouyang
 */
class CubeExpandAsset extends AssetBundle
{
    public $sourcePath = '@app/static/app';
    public $css = [
        'less/expand.less',
    ];
    public $js = [
        'http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js',
        'js/cube.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        // 'yii\web\JqueryAsset',
    ];
    public $publishOptions = [
        'only' => [
            '*.js',
            '*.less',
        ],
        'forceCopy' => YII_ENV_DEV,
    ];
}
