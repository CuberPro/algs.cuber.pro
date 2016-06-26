<?php

namespace app\controllers;

use Yii;
use app\models\db\Subsets;
use app\models\db\Cases;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class SubsetsController extends Controller {

    public function actionView($cubeId, $subsetName) {
        $subset = Subsets::find()
            ->where(['cube' => $cubeId, 'name' => $subsetName])
            ->with('cube0')
            ->with('cases')
            ->asArray()
            ->one();
        $cases = empty($subset) ? [] : $subset['cases'];
        array_walk($cases, function(&$item, $index) use ($subset) {
            $item['size'] = $subset['cube0']['size'];
            $item['name'] = isset($item['alias']) ? $item['alias'] : ($item['subset'] . ' ' .$item['sequence']);
            $item['view'] = $subset['view'];
            $algs = Cases::find()
                ->where(['cube' => $subset['cube'], 'subset' => $subset['name'], 'sequence' => $item['sequence']])
                ->with('algs')
                ->asArray()
                ->one();
            $item['algs'] = $algs['algs'];
        });
        $dataProvider = new ArrayDataProvider([
            'allModels' => $cases,
            'key' => function ($model) {
                return isset($model['alias']) ? $model['alias'] : $model['sequence'];
            },
            'pagination' => false,
        ]);
        return $this->render('view', [
            'cubeId' => $cubeId,
            'subsetName' => $subsetName,
            'dataProvider' => $dataProvider,
        ]);
    }
}
