<?php

namespace app\assets;

use yii\web\AssetBundle;

class EditCaseAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/edit-case.less',
    ];

    public $js = [
        'js/edit-case.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
        'yii\web\JqueryAsset',
    ];
}
