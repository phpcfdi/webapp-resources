<?php

return [

    /*
     * --------------------------------------------------------------------------
     * Dos Amigos User Module
     * --------------------------------------------------------------------------
     *
     * Implements User Management Module configuration
     */

    'class' => 'Da\User\Module',
    'administrators' => ['SuperAdmin'],
    'enableRegistration' => false,
    'classMap' => [
        'User' => app\models\UserAdmin::class,
        'LoginForm' => app\models\forms\admin\LoginAdminForm::class,
    ],
    'controllerMap' => [
        'security' => [
            'class' => 'Da\User\Controller\SecurityController',
            'layout' => '//login'
        ],
        'recovery' => [
            'class' => 'Da\User\Controller\RecoveryController',
            'layout' => '//login'
        ],
        'registration' => [
            'class' => 'Da\User\Controller\RegistrationController',
            'layout' => '//login'
        ],
        'admin' => [
            'class' => 'Da\User\Controller\AdminController',
            'layout' => '//settings'
        ],
        'role' => [
            'class' => 'Da\User\Controller\RoleController',
            'layout' => '//settings'
        ],
        'permission' => [
            'class' => 'Da\User\Controller\PermissionController',
            'layout' => '//settings'
        ],
        'rule' => [
            'class' => 'Da\User\Controller\RuleController',
            'layout' => '//settings'
        ]
    ],
    'mailParams' => [
        'fromEmail' => 'info@phpcfdi.mx'
    ],
];
