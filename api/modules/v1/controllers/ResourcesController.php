<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ResourcesController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * List of allowed domains.
     * Note: Restriction works only for AJAX (using CORS, is not secure).
     *
     * @return array List of domains, that can access to this API
     */
    public static function allowedDomains()
    {
        return [
            '*',                        // star allows all domains
        ];
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::class,
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],

        ]);
    }

    /**
     * Show all the projects info
     */
    public function actionIndex()
    {
        Yii::$app->response->format = 'json';

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

        return $projectsList;
    }

    /**
     * Show the info for only one project
     */
    public function actionView()
    {
        $params = Yii::$app->getRequest()->getBodyParams();

        Yii::$app->response->format = 'json';

        if (!isset($params['project'])) {
            $this->setHeader(400);
            return ['status' => 0, 'error_code' => 400, 'errors' => 'Parámetro requerido "project"'];
        }

        $projectInfo = $this->findResource($params['project']);

        // State files
        $stateFiles = FileHelper::findFiles(Yii::getAlias('@resources/') . $params['project'] . '/logs/', [
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
                'project' => $params['project'],
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

        return ['projectInfo' => $projectInfo, 'builds' => $dataProvider];
    }

    /**
     * Render the specific log for any resource execution time.
     * @param   string  $resource   Resource folder
     * @param   int     $execution  UNIX Timestamp of the execution
     * @return mixed
     */
    public function actionLog()
    {
        Yii::$app->response->format = 'json';

        $params = Yii::$app->getRequest()->getBodyParams();

        if ((!isset($params['resource'])) || (!isset($params['execution']))) {
            $this->setHeader(400);
            return ['status' => 0, 'error_code' => 400, 'errors' => 'Parámetros requeridos en req.body'];
        }

        $log = $this->findResourceLog($params['resource'], $params['execution']);
        $state = $this->findResourceState($params['resource'], $params['execution']);

        return [
            'log' => $log,
            'state' => $state
        ];
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
     * Finds the state of the executed file Ex. (xxxxx.state).
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

    private function setHeader($status)
    {

        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        $content_type = "application/json; charset=utf-8";

        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "PHP CFDI <phpcfdi.com>");
    }

    private function _getStatusCodeMessage($status)
    {
        $codes = array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
