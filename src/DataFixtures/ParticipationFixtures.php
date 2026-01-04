<?php

namespace App\DataFixtures;

use App\Factory\EvenementFactory;
use App\Factory\ParticipationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        // Participations démo
        ParticipationFactory::createOne([
            'utilisateur' => UserFactory::find(['email' => 'root@example.com']),
            'evenement' => EvenementFactory::random(),
            'dateInscription' => new \DateTime('-2 days'),
            'dateDesinscription' => null,
            'statut' => 'INSCRIT',
            'remarque' => null,
        ]);

        ParticipationFactory::createOne([
            'utilisateur' => UserFactory::find(['email' => 'user@example.com']),
            'evenement' => EvenementFactory::random(),
            'dateInscription' => new \DateTime('-1 days'),
            'dateDesinscription' => null,
            'statut' => 'INSCRIT',
            'remarque' => 'Hâte de participer.',
        ]);

        ParticipationFactory::createMany(30, fn () => [
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
