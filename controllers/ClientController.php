<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Client;
use yii\web\BadRequestHttpException;

class ClientController extends ActiveController
{
    public $modelClass = 'app\models\Client';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);

        return $actions;
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader !== null && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
            $token = $matches[1];

            $isValid = \app\components\JwtComponent::validateToken($token);
            if ($isValid) {
                Yii::info("Token válido", __METHOD__);
                return true;
            } else {
                Yii::error("Token inválido", __METHOD__);
            }
        } else {
            Yii::error("Cabeçalho Authorization não encontrado ou malformado", __METHOD__);
        }

        Yii::$app->response->statusCode = 401;
        echo \yii\helpers\Json::encode(['error' => 'Não autorizado, realize o login antes.']);
        Yii::$app->end();
        return false;
    }

    public function actionIndex($page = 1)
    {
        $pageSize = 10;
        $query = \app\models\Client::find();

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page - 1,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @return Client|array|string[]
     *
     * JSON Example
     * {
     *   "name": "Client Name",
     *   "cpf": "12345678900", only numbers
     *   "cep": "12345678", only numbers
     *   "address": "Client Address",
     *   "number": "Client Number",
     *   "city": "Client City",
     *   "state": "Client State",
     *   "complement": "Client Complement",
     *   "photo": "http://www.example.com/photo
     *   "sex": "M"
     * }
     */
    public function actionCreateClient()
    {
        $model = new Client();
        $params = Yii::$app->request->post();

        $model->name = $params['name'];
        $model->cpf = $params['cpf'];
        $model->cep = $params['cep'];
        $model->address = $params['address'];
        $model->number = $params['number'];
        $model->city = $params['city'];
        $model->state = $params['state'];
        $model->complement = $params['complement'];
        $model->photo = $params['photo'];
        $model->sex = $params['sex'];

        if ($model->save()) {
            return $model;
        } else {
            Yii::$app->response->statusCode = 400;
            return ['errors' => $model->errors];
        }
    }
}