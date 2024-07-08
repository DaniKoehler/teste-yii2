<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'login') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $login = $request->post('login');
        $password = $request->post('password');

        $user = User::findByUsername($login);
        if ($user !== null && $user->validatePassword($password)) {
            $token = $user->generateJwtToken();
            Yii::$app->session->set('jwtToken', $token);
            return ['token' => $token];
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Unauthorized'];
        }
    }
}