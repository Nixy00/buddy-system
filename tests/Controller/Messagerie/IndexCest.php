<?php

namespace App\Tests\Controller\Messagerie;

use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function testMessagerieRedirect(ControllerTester $I)
    {
        $I->amOnPage('/messagerie');
        // Redirige vers connexion si non connecté
        $I->seeInCurrentUrl('/connexion');
    }
}
