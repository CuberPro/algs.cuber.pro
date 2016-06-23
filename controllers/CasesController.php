<?php

namespace app\controllers;

use Yii;
use app\models\db\Cases;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

/**
 * CasesController implements the CRUD actions for Cases model.
 */
class CasesController extends Controller {

    public function actionView($cubeId, $subsetName, $caseName) {
        $case = Cases::find()
            ->where([
                'AND',
                ['cube' => $cubeId],
                ['subset' => $subsetName],
                [
                    'OR',
                    ['sequence' => $caseName],
                    ['alias' => $caseName],
                ],
            ])
            ->with('cube0')
            ->with('subset0')
            ->with('algs')
            ->asArray()
            ->one();
        $case['size'] = $case['cube0']['size'];
        $case['name'] = $case['subset'] . ' ' . (isset($case['alias']) ? $case['alias'] : $case['sequence']);
        $case['view'] = $case['subset0']['view'];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $case['algs'],
        ]);
        return $this->render('view', [
            'cubeId' => $cubeId,
            'subsetName' => $subsetName,
            'model' => $case,
            'dataProvider' => $dataProvider,
        ]);
    }
}
