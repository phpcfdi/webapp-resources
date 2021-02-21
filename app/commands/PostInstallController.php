<?php

namespace app\commands;

use Da\Config\Configuration;
use Yii;
use yii\console\Controller;

class PostInstallController extends Controller
{
    /**
     * This command generates the cookie validation on the .env file after project has been installed by composer.
     */
    public function actionGenerateCookieValidation()
    {
        $key = Yii::$app->security->generateRandomString();
        Configuration::env()->changeEnvironmentFile(['APP_COOKIE_VALIDATION_KEY' => $key]);
    }
}
