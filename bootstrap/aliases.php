<?php

use SideKit\Config\ConfigKit;

/*
 * --------------------------------------------------------------------------
 * Register custom Yii aliases
 * --------------------------------------------------------------------------
 *
 * As we have changed the structure. Modify default Yii aliases here.
 */
Yii::setAlias('@domainName', (YII_ENV === 'dev') ? '/webapp-resources/public_html' : 'https://www.webapp-resources.mx');
//Yii::setAlias('@domainName', (YII_ENV === 'dev') ? '/webapp-resources/website' : 'https://www.website.webapp-resources.mx');

Yii::setAlias('@website', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../public_html');
//Yii::setAlias('@website', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../website');

Yii::setAlias('@admin', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../admin');

Yii::setAlias('@api', ConfigKit::config()->getBasePath() . DIRECTORY_SEPARATOR . '../api');
