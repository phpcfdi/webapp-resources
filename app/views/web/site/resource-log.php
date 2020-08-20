<?php

/* @var $this yii\web\View */

$this->title = $state['project'] . ' - ' . $state['date'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recurso') . ': ' . $state['project'], 'url' => ['/site/resource', 'project' => $state['project']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <h1><?= $this->title ?></h1>
    <hr>
    <b>Actualizado el: </b> <?= date('d/m/Y H:i:s', $state['date']) ?><br>
    <b>Estado: </b> <?= $state['state'] ?><br>
    <b>Cambio(s): </b> <?= $state['change'] ?><br>

    <hr>

    <pre><?= $log ?></pre>
</div>