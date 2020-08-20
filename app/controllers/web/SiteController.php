<?php

namespace app\controllers\web;

use app\models\ContactForm;
use app\models\LoginForm;
use Faker\Provider\File;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     * @return string
     */
    public function actionIndex()
    {
        $resourcesDirectories = FileHelper::findDirectories(Yii::getAlias('@resources'), [
            'recursive' => false
        ]);

        $projectsUpdated = [];

        $i = 0;
        // Showing state.json
        foreach ($resourcesDirectories as $key => $directory) {
            $stateFile = Json::decode(file_get_contents($directory . "/state.json"));

            ArrayHelper::setValue($projectsUpdated, ['id' => $i], [
                'project' => $stateFile['project'],
                'date' => $stateFile['date']
            ]);

            $i++;
        }

        return $this->render('index', [
            'projectsUpdated' => $projectsUpdated
        ]);
    }

    /**
     * @param   string  $resource resource name
     * @return mixed
     */
    public function actionResource(string $project)
    {
        $projectInfo = $this->findResource($project);

        //.logs and .state
        $projectFiles = FileHelper::findFiles(Yii::getAlias('@resources/') . $project . '/logs/', [
            'except' => [
                '.gitignore'
            ]
        ]);

        return $this->render('resource', [
            'projectInfo' => $projectInfo,
            'projectFiles' => $projectFiles
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['mail']['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Finds the project file based on its project name (state.json).
     * If the file is not found, a 404 HTTP exception will be thrown.
     * @param string $project Project name
     * @return mixed the PHP Data
     * @throws NotFoundHttpException if the file cannot be found
     */
    protected function findResource(string $project)
    {
        if (($resource = file_get_contents(Yii::getAlias('@resources/') . $project . '/state.json'))) {
            return Json::decode($resource);
        }

        throw new NotFoundHttpException(Yii::t('app', 'La página que usted solicitó no existe'));
    }
}
