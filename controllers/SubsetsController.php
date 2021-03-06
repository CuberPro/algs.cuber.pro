<?php

namespace app\controllers;

use Yii;
use app\models\db\Subsets;
use app\models\db\CasesInSubset;
use app\models\db\Cases;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class SubsetsController extends Controller {

    public function actionView($cubeId, $subsetName) {
        $cases = CasesInSubset::find()
            ->where(['cube' => $cubeId, 'subset' => $subsetName])
            ->with('cube0')
            ->with('subset0')
            ->with('case0')
            ->orderBy(['sequence' => SORT_ASC])
            ->all();
        array_walk($cases, function (&$item, $index) {
            $itemArray = $item->toArray($item->fields(), $item->extraFields());
            $itemArray['name'] = $item->name;
            $item = $itemArray;
            $item['size'] = $item['cube0']['size'];
            $item['view'] = $item['subset0']['view'];
            $algs = Cases::find()
                ->where(['id' => $item['case']])
                ->with([
                    'algs' => function ($query) {
                        $query->limit(Yii::$app->params['subset.maxAlgShown']);
                    },
                ])
                ->asArray()
                ->one();
            $item['algs'] = $algs['algs'];
            $item['state'] = $item['case0']['state'];
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
