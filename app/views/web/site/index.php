<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <h1>Recursos</h1>

    <ul>
        <?php foreach ($projectsUpdated as $project) { ?>
            <li>
                <?= Html::a($project['project'], ['/site/resource', 'project' => $project['project']]) .
                    ' ' . Yii::t('app', 'Actualizado el: ')
                    . date('Y-m-d H:i:s', $project['date']) ?>
            </li>
        <?php } ?>
    </ul>
</div>