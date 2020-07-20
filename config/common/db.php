<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=DB_HOST;dbname=DB_NAME',
    'username' => 'DB_USER',
    'password' => 'DB_PASS',
    'charset' => 'utf8',
    'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
];
