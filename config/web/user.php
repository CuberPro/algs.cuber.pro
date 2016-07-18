<?php

return [
    'identityClass' => 'app\models\user\Users',
    'enableAutoLogin' => true,
    'autoRenewCookie' => true,
    'loginUrl' => ['user/login'],
];