<?php

namespace app\assets;

use yii\web\AssetBundle;

class CubeSetAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/cube-set.less',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}