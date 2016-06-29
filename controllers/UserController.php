<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\LoginForm;

class UserController extends Controller {

    public $defaultAction = 'login';

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

    public function actionLogout($u = '/') {
        Yii::$app->user->logout();
        $this->redirect(Url::toRoute([$u]));
    }
}
