<?php

namespace App\DataFixtures;

use App\Factory\PossessionFactory;
use App\Factory\ProfilFactory;
use App\Factory\RoleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $roleAdmin = RoleFactory::find(['code' => 'ROLE_ADMIN']);
        $roleUser = RoleFactory::find(['code' => 'ROLE_USER']);

        // Admin
        $admin = UserFactory::createOne([
            'login' => 'admin',
            'email' => 'root@example.com',
            'dateCrea' => new \DateTime(),
        ]);

        ProfilFactory::createOne([
            'utilisateur' => $admin,
            'nom' => 'Stark',
            'prenom' => 'Tony',
            'genre' => 'Homme',
            'typeCompte' => 'admin',
            'dateNaissance' => new \DateTime('1970-05-29'),
            'biographie' => 'Administrateur système du Buddy System.',
            'dateCrea' => new \DateTime(),
            'dateModif' => new \DateTime(),
        ]);

        PossessionFactory::createOne([
            'utilisateur' => $admin,
            'role' => $roleAdmin,
        ]);

        // User démo
        $user = UserFactory::createOne([
            'login' => 'user',
            'email' => 'user@example.com',
            'dateCrea' => new \DateTime(),
        ]);

        ProfilFactory::createOne([
            'utilisateur' => $user,
            'nom' => 'Parker',
            'prenom' => 'Peter',
            'genre' => 'Homme',
            'typeCompte' => 'user',
            'dateNaissance' => new \DateTime('2001-08-10'),
            'biographie' => 'Étudiant en photo-journalisme, cherche un parrain sympa !',
            'dateCrea' => new \DateTime(),
            'dateModif' => new \DateTime(),
        ]);

        PossessionFactory::createOne([
            'utilisateur' => $user,
            'role' => $roleUser,
        ]);

        // Users aléatoires
        $users = UserFactory::createMany(10);

        foreach ($users as $randomUser) {
            ProfilFactory::createOne([
                'utilisateur' => $randomUser,
                'dateCrea' => new \DateTime(),
                'dateModif' => new \DateTime(),
            ]);

            PossessionFactory::createOne([
                'utilisateur' => $randomUser,
                'role' => $roleUser,
            ]);
        }
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }
}
