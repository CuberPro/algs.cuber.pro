<?php

return [
    'linkAssets' => true,
    'converter' => [
        'class' => 'yii\web\AssetConverter',
        'commands' => [
            'less' => ['css', 'lessc {from} {to} --no-color --autoprefix' . (YII_ENV_DEV ? ' --source-map' : ' --clean-css')],
            'ts' => ['js', 'tsc --target es5 --sourceMap --outFile {to} {from}'],
        ],
    ],
];
