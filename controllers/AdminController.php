<?php

namespace app\controllers;

use app\models\cube\CubeNNN;
use app\models\cube\Algorithm;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $cube = new CubeNNN(3);
        $algo = new Algorithm("u R2 L'");
        $cube->apply($algo);
        return $this->render('index', [
            'cubeSize' => $cube->size,
            'cubeString' => $cube->getStickersString(),
        ]);
    }

}
