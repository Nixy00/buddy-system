<?php

namespace App\Tests\Controller\Legal;

use App\Tests\Support\ControllerTester;

class CguCest
{
    public function testCguPage(ControllerTester $I)
    {
        $I->amOnPage('/cgu');
        $I->seeResponseCodeIs(200);
    }
}
