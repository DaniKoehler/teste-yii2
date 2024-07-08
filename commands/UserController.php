<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class UserController extends Controller
{
    public function actionCreate($login, $password, $name)
    {
        if (empty($login) || empty($password) || empty($name)) {
            echo "Todos os campos são obrigatórios: login, senha e nome.\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user = new User();
        $user->login = $login;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->name = $name;

        if ($user->save()) {
            echo "Usuário '{$name}' criado com sucesso.\n";
            return ExitCode::OK;
        } else {
            echo "Erro ao criar usuário.\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}