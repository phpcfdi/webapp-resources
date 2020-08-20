<?php

namespace app\controllers\web;

use app\models\ContactForm;
use app\models\LoginForm;
use Faker\Provider\File;
use Yii;
use yii\data\ArrayDataProvider;
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

        asort($resourcesDirectories);

        $projectsList = [];

        $i = 0;
        // Showing state.json
        foreach ($resourcesDirectories as $key => $directory) {

            if (!$stateFile = file_get_contents($directory . "/state.json")) {
                throw new \InvalidArgumentException(Yii::t('app', 'Verificar que se cuente con el archivo state.json'));
            }

            $stateFile = Json::decode($stateFile);

            ArrayHelper::setValue($projectsList, ['id' => $i], [
                'project' => $stateFile['project'],
                'date' => $stateFile['date']
            ]);

            $i++;
        }

        return $this->render('index', [
            'projectsList' => $projectsList
        ]);
    }

    /**
     * @param string $project project name
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionResource(string $project)
    {
        $projectInfo = $this->findResource($project);

        //.state files
        $stateFiles = FileHelper::findFiles(Yii::getAlias('@resources/') . $project . '/logs/', [
            'only' => [
                '*.state'
            ]
        ]);

        $statesList = [];

        $i = 0;

        foreach ($stateFiles as $directory) {
            if (!$stateFile = Json::decode(file_get_contents($directory))) {
                throw new \InvalidArgumentException(Yii::t('app', 'Verificar que se cuente con el archivo state.json'));
            }

            ArrayHelper::setValue($statesList, ['id' => $i], [
                'date' => $stateFile['date'],
                'state' => $stateFile['state'],
                'project' => $project,
            ]);

            $i++;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $statesList,
            'sort' => [
                'attributes' => ['date'],
                'defaultOrder' => [
                    'date' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('resource', [
            'projectInfo' => $projectInfo,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Render the specific log for any resource execution time.
     * @param   string  $resource   Resource folder
     * @param   int     $execution  UNIX Timestamp of the execution
     * @return mixed
     */
    public function actionResourceLog(string $resource, int $execution)
    {
        $log = $this->findResourceLog($resource, $execution);
        $state = $this->findResourceState($resource, $execution);

        return $this->render('resource-log', [
            'log' => $log,
            'state' => $state
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
        if (($resource = @file_get_contents(Yii::getAlias('@resources/') . $project . '/state.json')) === false) {
            throw new NotFoundHttpException(Yii::t('app', 'La página que usted solicitó no existe'));
        }
        
        return Json::decode($resource);
    }

    /**
     * Finds the log of the executed file Ex. (xxxxx.log).
     * If the file is not found, a 404 HTTP exception will be thrown.
     * @param string $project Project name
     * @param int $timestamp The timestamp of the execution.
     * @return mixed the PHP Data
     * @throws NotFoundHttpException if the file cannot be found
     */
    protected function findResourceLog(string $project, int $timestamp)
    {
        if (!($resource = @file_get_contents(Yii::getAlias('@resources/') . $project . '/logs/' . $timestamp . '.log'))) {
            throw new NotFoundHttpException(Yii::t('app', 'La página que usted solicitó no existe'));
        }
        
        return $resource;
    }

    /**
     * Finds the state of the executed file Ex. (xxxxx.log).
     * If the file is not found, a 404 HTTP exception will be thrown.
     * @param string $project Project name
     * @param int $timestamp The timestamp of the execution.
     * @return mixed the PHP Data
     * @throws NotFoundHttpException if the file cannot be found
     */
    protected function findResourceState(string $project, int $timestamp)
    {
        if (!($resource = @file_get_contents(Yii::getAlias('@resources/') . $project . '/logs/' . $timestamp . '.state'))) {
            throw new NotFoundHttpException(Yii::t('app', 'La página que usted solicitó no existe'));
        }
        
        return Json::decode($resource);
    }
}
