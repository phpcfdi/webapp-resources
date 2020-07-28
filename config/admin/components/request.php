<?php

use SideKit\Config\ConfigKit;

return [
    'cookieValidationKey' => ConfigKit::env()->get('APP_COOKIE_VALIDATION_KEY'),
    /* 'csrfParam' => '_csrf-dashboard',
    'csrfCookie' => [
        'httpOnly' => true,
        'path' => '/dashboard',
    ], */
];
