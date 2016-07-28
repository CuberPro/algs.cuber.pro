<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'routeParam' => 'rt', // conflicts with visualcube parameters
    'rules' => [
        'oauth/<action>' => 'o-auth/<action>',
        '<cubeId:(?!admin|user|cases)[0-9A-Za-z_ -]+>/<subsetName>/<caseName>' => 'cases/view',
        '<cubeId:(?!admin|user|cases)[0-9A-Za-z_ -]+>/<subsetName>' => 'subsets/view',
        '<cubeId:(?!admin|user|cases)[0-9A-Za-z_ -]+>' => 'cubes/view',
    ],
];
