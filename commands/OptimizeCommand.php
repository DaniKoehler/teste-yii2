<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class OptimizeCommand extends Controller
{
    public function actionIndex()
    {
        Yii::$app->cache->flush();

        echo "Cache cleared.\n";
        return ExitCode::OK;
    }
}