<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => false,
    'rules' => [
        '<cubeId:(?!admin|user)[0-9A-Za-z_ -]+>/<subsetName>/<caseName>' => 'cases/view',
        '<cubeId:(?!admin|user)[0-9A-Za-z_ -]+>/<subsetName>' => 'subsets/view',
        '<cubeId:(?!admin|user)[0-9A-Za-z_ -]+>' => 'cubes/view',
    ],
];