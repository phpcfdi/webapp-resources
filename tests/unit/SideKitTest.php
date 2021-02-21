<?php

use Da\Config\Configuration;

class SideKitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    // tests
    public function testEnviroment()
    {
        expect(Configuration::env()->get('DATABASE_DSN_DB'))->equals('yii2_tests');
    }
}
