<?php

namespace app\assets;

use yii\web\AssetBundle;

class CaseViewAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/case-view.less',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}