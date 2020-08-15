<?php

use SideKit\Config\ConfigKit;

/*
 * --------------------------------------------------------------------------
 * Register custom Yii aliases
 * --------------------------------------------------------------------------
 *
 * As we have changed the structure. Modify default Yii aliases here.
 */
Yii::setAlias('@website', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../public_html');

Yii::setAlias('@admin', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../admin');

Yii::setAlias('@api', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../api');

Yii::setAlias('@resources', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../projects');
