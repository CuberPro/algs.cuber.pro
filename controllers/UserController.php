<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\LoginForm;
use app\models\SignupForm;

class UserController extends Controller {

    public $defaultAction = 'login';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'signup', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => [$this, 'accessDenied'],
            ]
        ];
    }

    public function accessDenied($rule, $action) {
        $request = Yii::$app->request;
        $u = Url::toRoute([$request->get('u', '/')]);
        switch ($action->id) {
            case 'login':
            case 'signup':
            case 'logout':
                $this->redirect($u);
                return;
            default:
                throw new ForbiddenHttpException('You are not allowed to access this page');
                return;
        }
    }

    public function actionLogin($u = '/') {
        $request = Yii::$app->request;
        $model = new LoginForm;
        $model->u = Url::toRoute([$u]);
        if ($request->isPost) {
            $model->load($request->post());
            $success = $model->login();
            if ($success) {
                return $this->redirect($model->u);
            }
        }
        $model->plainPassword = null;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup($u = '/') {
        $request = Yii::$app->request;
        $model = new SignupForm;
        $model->u = Url::toRoute([$u]);
        if ($request ->isPost) {
            $model->load($request->post());
            $success = $model->signup();
            if ($success) {
                return $this->redirect($model->u);
            }
        }
        $model->plainPassword = null;
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout($u = '/') {
        Yii::$app->user->logout();
        $this->redirect(Url::toRoute([$u]));
    }
}
