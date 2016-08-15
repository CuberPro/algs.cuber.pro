<?php

namespace app\assets;

use yii\web\AssetBundle;

class UserProfileAsset extends AssetBundle {

    public $sourcePath = '@app/static/app';

    public $js = [
      'js/user-profile.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
        'app\assets\UserAsset',
        'yii\web\JqueryAsset',
        'yii\authclient\widgets\AuthChoiceAsset',
    ];
}
