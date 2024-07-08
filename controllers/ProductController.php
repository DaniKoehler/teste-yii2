<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Product;
use yii\web\BadRequestHttpException;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';

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

    public function actionIndex($cpf = null)
    {
        $query = \app\models\Product::find();

        if ($cpf !== null) {
            $client = \app\models\Client::findOne(['cpf' => $cpf]);
            if ($client !== null) {
                $query->andWhere(['client_id' => $client->id]);
            } else {
                Yii::$app->response->statusCode = 404;
                return ['error' => 'Cliente não encontrado com o CPF fornecido.'];
            }
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * @return Product|array|string[]
     *
     * JSON Example
     * {
     *   "name": "Product Name",
     *   "price": 100.00,
     *   "cpf": "12345678900", only numbers
     *   "photo": "http://www.example.com/photo.jpg"
     * }
     */
    public function actionCreateProduct()
    {
        $model = new Product();
        $params = Yii::$app->request->post();

        $client = \app\models\Client::findOne(['cpf' => $params['cpf']]);
        if ($client === null) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Cliente não encontrado com o CPF fornecido.'];
        }

        $model->client_id = $client->id;

        $model->name = $params['name'];
        $model->price = $params['price'];
        $model->photo = $params['photo'];

        if ($model->save()) {
            return $model;
        } else {
            Yii::$app->response->statusCode = 400;
            return ['errors' => $model->errors];
        }
    }
}