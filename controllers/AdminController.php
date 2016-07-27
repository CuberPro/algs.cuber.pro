<?php

namespace app\controllers;

use Yii;
use app\models\cube\CubeNNN;
use app\models\cube\Algorithm;
use app\models\db\Cases;
use yii\web\Controller;

class AdminController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    'admin' => [
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ],
            ]
        ];
    }

    public function actionIndex() {
        $cube = new CubeNNN(3);
        $algo = new Algorithm("R U R' x");
        $rev = $algo->getReverse(true);
        $cube->apply($algo);
        return $this->render('index', [
            'cubeSize' => $cube->size,
            'cubeString' => $cube->getStickersString(),
        ]);
    }

    public function actionEditCase($id) {
        $case = Cases::find()
            ->where(['id' => $id])
            ->with('subsets')
            ->with('cube')
            ->asArray()
            ->one();
        return $this->render('edit-case', [
            'case' => $case,
        ]);
    }

}
