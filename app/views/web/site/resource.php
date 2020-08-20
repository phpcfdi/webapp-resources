<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Recurso') . ': ' . $projectInfo['project'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= $projectInfo['project'] ?></h1>
    <hr>
    <b>Actualizado el: </b> <?= date('d/m/Y H:i:s', $projectInfo['date']) ?><br>
    <b>Estado: </b> <?= $projectInfo['state'] ?><br>
    <b>Cambio(s): </b> <?= $projectInfo['change'] ?><br>
</div>