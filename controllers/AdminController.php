<?php

namespace app\controllers;

use Yii;
use app\models\cube\CubeNNN;
use app\models\cube\Algorithm;
use app\models\Users;
use yii\web\Controller;

class AdminController extends Controller
{
    public function actionIndex()
    {
        $cube = new CubeNNN(3);
        $algo = new Algorithm("R U R' x");
        $rev = $algo->getReverse(true);
        $cube->apply($algo);
        return $this->render('index', [
            'cubeSize' => $cube->size,
            'cubeString' => $cube->getStickersString(),
        ]);
    }

}
