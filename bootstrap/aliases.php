<?php

use Da\Config\Configuration;

/*
 * --------------------------------------------------------------------------
 * Register custom Yii aliases
 * --------------------------------------------------------------------------
 *
 * As we have changed the structure. Modify default Yii aliases here.
 */

Yii::setAlias('@website', Configuration::app()->getBasePath() . DIRECTORY_SEPARATOR . '../public_html');

Yii::setAlias('@api', Configuration::app()->getBasePath() . DIRECTORY_SEPARATOR . '../api');

Yii::setAlias('@resources', call_user_func(
    function (): string {
        $envResources = strval(Configuration::env()->get('DIR_PROJECTS')) ?: 'projects';
        if (DIRECTORY_SEPARATOR === substr($envResources, 0, 1)) { // absolute path
            return $envResources;
        }

        // relative path
        return dirname(Configuration::app()->getBasePath()) . DIRECTORY_SEPARATOR . $envResources;
    }
));
