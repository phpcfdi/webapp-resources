<?php

return [
'class' => 'yii\web\AssetManager',
'bundles' => [
        'yii\web\JqueryAsset' => [
            'js' => [
                YII_ENV_DEV ? 'jquery.min.js' : 'jquery.min.js'
            ]
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'css' => [
                YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
            ]
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'js' => [
                YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
            ]
            ],
        'yii\web\YiiAsset' => false,
    ],
];
