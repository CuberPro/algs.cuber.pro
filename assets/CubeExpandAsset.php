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
