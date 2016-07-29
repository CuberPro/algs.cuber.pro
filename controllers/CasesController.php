<?php

namespace app\controllers;

use Yii;
use app\models\db\CasesInSubset;
use app\models\db\Cases;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CasesController implements the CRUD actions for Cases model.
 */
class CasesController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'only' => ['update'],
                'rules' => [
                    'adminCanUpdate' => [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['editCase'],
                        'verbs' => ['post'],
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    $data = [
                        'success' => false,
                        'message' => 'Access denied',
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $data;
                }
            ]
        ];
    }

    public function actionView($cubeId, $subsetName, $caseName) {
        $case = CasesInSubset::find()
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
            ->with([
                'case0' => function ($query) {
                    $query->with('algs')->asArray();
                }
            ])
            ->one();
        if (!$case) {
            throw new NotFoundHttpException('Case not found');
        }
        $caseArray = $case->toArray($case->fields(), $case->extraFields());
        $caseArray['name'] = $case->getName();
        $case = $caseArray;
        $case['algs'] = $case['case0']['algs'];
        $case['size'] = $case['cube0']['size'];
        $case['view'] = $case['subset0']['view'];
        $case['state'] = $case['case0']['state'];
        $dataProvider = new ArrayDataProvider([
            'allModels' => $case['algs'],
            'pagination' => false,
        ]);
        return $this->render('view', [
            'cubeId' => $cubeId,
            'subsetName' => $subsetName,
            'model' => $case,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate() {
        $data = [];
        do {
            $currentCase = Cases::findOne(Yii::$app->request->post('id'));
            if (!$currentCase) {
                $data = [
                    'success' => false,
                    'message' => 'Cannot find the case',
                ];
                break;
            }
            $newStickers = Yii::$app->request->post('stickers');
            $newId = md5($newStickers);
            $newCase = Cases::findOne($newId);
            if ($newCase) {
                $data = [
                    'success' => false,
                    'message' => 'Duplicated cases',
                ];
                break;
            }
            $currentCase->id = $newId;
            $currentCase->state = $newStickers;
            if (!$currentCase->save()) {
                $data = [
                    'success' => false,
                    'message' => 'Save failed',
                ];
                break;
            }
            $data = [
                'success' => true,
                'data' => [
                    'id' => $newId,
                ],
            ];
        } while (0);
        $response = new Response([
            'format' => Response::FORMAT_JSON,
            'data' => $data,
        ]);
        return $response;
    }
}
