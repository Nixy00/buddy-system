<?php

namespace App\Tests\Controller\Registration;

use App\Tests\Support\ControllerTester;

class RegisterCest
{
    public function testRegisterPage(ControllerTester $I)
    {
        $I->amOnPage('/inscription');
        $I->seeResponseCodeIs(200);
    }
}
