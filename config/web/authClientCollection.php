<?php

return [
    'class' => '\yii\authclient\Collection',
    'clients' => [
        'wca' => [
            'class' => 'app\models\auth\clients\Wca',
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'scope' => 'public email',
        ],
        'facebook' => [
            'class' => 'app\models\auth\clients\Facebook',
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'scope' => 'email',
        ],
        'twitter' => [
            'class' => 'app\models\auth\clients\Twitter',
            'consumerKey' => 'consumer_key',
            'consumerSecret' => 'consumer_secret',
        ],
        'google' => [
            'class' => 'app\models\auth\clients\Google',
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'scope' => 'email',
        ],
        'github' => [
            'class' => 'app\models\auth\clients\GitHub',
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'scope' => 'user',
        ],
    ],
];
