<?php

namespace App\Tests\Controller\Security;

use App\Tests\Support\ControllerTester;

class LoginCest
{
    public function testLoginPage(ControllerTester $I)
    {
        $I->amOnPage('/connexion');
        $I->seeResponseCodeIs(200);
    }
}
