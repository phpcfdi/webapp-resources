<?php

namespace api\modules\v1\controllers;

use yii\helpers\Json;
use yii\rest\Controller;

class TestsController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        /*$behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];*/
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
     * @param   int     $id  [$id description]
     * @return  string       [return description]
     */
    public function actionOther(int $id): string
    {
        $attributes = \Yii::$app->request->get();

        return 'Action Other !! - ' . $attributes['id'];
    }
}
