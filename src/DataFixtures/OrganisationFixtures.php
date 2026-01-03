<?php

namespace App\DataFixtures;

use App\Factory\EvenementFactory;
use App\Factory\OrganisationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::find(['email' => 'root@example.com']);

        // démo : l'admin organise 3 évènements
        for ($i = 0; $i < 3; ++$i) {
            OrganisationFactory::createOne([
                'utilisateur' => $admin,
                'evenement' => EvenementFactory::random(),
            ]);
        }

        OrganisationFactory::createMany(20, fn () => [
            'utilisateur' => UserFactory::random(),
            'evenement' => EvenementFactory::random(),
        ]);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            EvenementFixtures::class,
        ];
    }
}
