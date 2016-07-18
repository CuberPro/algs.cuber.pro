<?php

namespace app\assets;

use yii\web\AssetBundle;

class UserAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/user.less',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
