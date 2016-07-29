<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\db\Cubes;
use app\models\db\Subsets;
use yii\data\ArrayDataProvider;

class CubesController extends Controller {

    public function actionIndex() {
        $cubes = Cubes::find()
            ->orderBy(['size' => SORT_ASC])
            ->asArray()
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $cubes,
            'key' => 'id',
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($cubeId) {
        $cube = Cubes::findOne($cubeId);
        if (!$cube) {
            throw new NotFoundHttpException('Cube not found');
        }
        $subsets = $cube->getSubsets()
            ->asArray()
            ->all();
        array_walk($subsets, function (&$item, $index) use ($cube, $cubeId) {
            $item['size'] = $cube['size'];
            $cases = Subsets::find()
                ->where(['cube' => $cubeId, 'name' => $item['name']])
                ->with('cases')
                ->asArray()
                ->one()['cases'];
            $repKey = array_rand($cases);
            $item['state'] = !empty($cases[$repKey]['state']) ? $cases[$repKey]['state'] : null;
        });
        $dataProvider = new ArrayDataProvider([
            'allModels' => $subsets,
            'key' => 'name',
            'pagination' => false,
        ]);

        return $this->render('view', [
            'cubeId' => $cubeId,
            'dataProvider' => $dataProvider,
        ]);
    }
}
