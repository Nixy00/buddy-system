<?php

namespace App\Tests\Controller\Dashboard;

use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function testDashboardRequiresAuth(ControllerTester $I)
    {
        $I->amOnPage('/dashboard');
        // Redirige vers connexion si non connecté
        $I->seeInCurrentUrl('/connexion');
    }
}
