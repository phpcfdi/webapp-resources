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

    'id' => 'webapp-resources-web',

    'name' => 'phpCfdi - Recursos',

    'basePath' => Configuration::app()->getBasePath(),

    'vendorPath' => Configuration::app()->getVendorPath(),

    'runtimePath' => Configuration::app()->getRuntimePath(),

    'language' => Configuration::env()->get('APP_LANGUAGE'),

    'bootstrap' => ['log'],

    'controllerNamespace' => 'app\controllers\web',

    //Site ofline only require to uncomment this key in the config array
    //'catchAll' => ['site/offline']
];
