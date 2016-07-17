<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/site.less',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
