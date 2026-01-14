<?php

namespace App\Tests\Controller\Legal;

use App\Tests\Support\ControllerTester;

class ContactCest
{
    public function testContactPage(ControllerTester $I)
    {
        $I->amOnPage('/contact');
        $I->seeResponseCodeIs(200);
    }
}
