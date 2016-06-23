<?php

namespace app\assets;

use yii\web\AssetBundle;

class SubsetListAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/subset-list.less',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}