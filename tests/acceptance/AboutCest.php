<?php
use yii\helpers\Url as Url;

class AboutCest
{
    public function ensureThatAboutWorks(\AcceptanceTester $I)
    {
        $I->amOnPage('/site/about');
        $I->see('Acerca de', 'h1');
    }
}
