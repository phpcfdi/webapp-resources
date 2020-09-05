<?php

namespace api\modules\v1\controllers;

use yii\helpers\Json;
use yii\rest\Controller;

class TestsController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * [First endpoint]
     * @return  string
     */
    public function actionIndex(): string
    {
        return Json::encode(['message' => 'Welcome to WebApp Resources API']);
    }

    /**
     * [actionOther description]
     */
    public function actionOther(): string
    {
        $attributes = \Yii::$app->request->get();

        return "Another action !! - $attributes";
    }
}
