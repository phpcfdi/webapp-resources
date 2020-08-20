<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Recurso') . ': ' . $projectInfo['project'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= $projectInfo['project'] ?></h1>
    <hr>
    <b>Actualizado el: </b> <?= date('d/m/Y H:i:s', $projectInfo['date']) ?><br>
    <b>Estado: </b> <?= $projectInfo['state'] ?><br>
    <b>Cambio(s): </b> <?= $projectInfo['change'] ?><br>

    <hr>

    <?= GridView::widget([
        'id' => 'builds-index',
        'dataProvider' => $dataProvider,
        'layout' => '{items} {pager}',
        'columns' => [
            [
                'attribute' => 'date',
                'label' => Yii::t('app', 'Ejecución')
            ],
            [
                'attribute' => 'state',
                'label' => Yii::t('app', 'Estado')
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $url = ['/site/resource-log', 'resource' => $model['project'], 'execution' => $model['date']];
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url , [
                            'class' => 'blue',
                            'data' => [
                                'title' => Yii::t('app', 'Ver el log')
                            ]
                        ]);
                    }
                ]
            ]
        ],
    ]); ?>
</div>