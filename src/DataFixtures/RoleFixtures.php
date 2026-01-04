<?php

namespace App\DataFixtures;

use App\Factory\RoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        RoleFactory::createOne([
            'code' => 'ROLE_ADMIN',
            'libelle' => 'Administrateur',
        ]);

        RoleFactory::createOne([
            'code' => 'ROLE_USER',
            'libelle' => 'Utilisateur',
        ]);

        $manager->flush();
    }
}
