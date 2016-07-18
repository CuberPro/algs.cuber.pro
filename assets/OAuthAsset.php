<?php

namespace app\assets;

use yii\web\AssetBundle;

class OAuthAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';
    public $css = [
        'less/oauth.less',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
