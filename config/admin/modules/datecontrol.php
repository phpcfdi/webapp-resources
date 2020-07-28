<?php

use \kartik\datecontrol\Module;

return [
    'class' => '\kartik\datecontrol\Module',

    // format settings for displaying each date attribute (ICU format example)
    'displaySettings' => [
        Module::FORMAT_DATE => 'dd/MM/yyyy',
        Module::FORMAT_TIME => 'hh:mm:ss a',
        Module::FORMAT_DATETIME => 'dd/MM/yyyy hh:mm:ss a',
    ],

    // format settings for saving each date attribute (PHP format example)
    'saveSettings' => [
        Module::FORMAT_DATE => 'php:Y-m-d',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],

    // set your display timezone
    //'displayTimezone' => 'America/New_York',

    // set your timezone for date saved to db
    //'saveTimezone' => 'UTC',

    // automatically use kartik\widgets for each of the above formats
    'autoWidget' => true,

    // default settings for each widget from kartik\widgets used when autoWidget is true
    'autoWidgetSettings' => [
        Module::FORMAT_DATE => ['type' => 2, 'pluginOptions' => ['autoclose' => true]],
        Module::FORMAT_DATETIME => [],
        Module::FORMAT_TIME => [],
    ]
];
