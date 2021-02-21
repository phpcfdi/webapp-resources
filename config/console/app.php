<?php

use Da\Config\Configuration;

return [

    /*
    * --------------------------------------------------------------------------
    * Application
    * --------------------------------------------------------------------------
    *
    * Base class for all application classes. Here we configure the attributes
    * that do not hold any object configuration such as "components" or
    * "modules". The configuration of those properties are within submodules of
    * the same name.
    */

    'id' => 'webapp-resources-console',

    'basePath' => Configuration::app()->getBasePath(),

    'vendorPath' => Configuration::app()->getVendorPath(),

    'runtimePath' => Configuration::app()->getRuntimePath(),

    'language' => Configuration::env()->get('APP_LANGUAGE'),

    'bootstrap' => ['log'],

    'controllerNamespace' => 'app\commands',

    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationNamespaces' => [
                'Da\User\Migration',
            ],
            'migrationPath' => [
                '@app/migrations/2020'
            ],
        ],
    ],
];
