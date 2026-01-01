<?php

namespace App\DataFixtures;

use App\Factory\EvenementFactory;
use App\Factory\ParticipationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class ParticipationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \DateMalformedStringException
     * @throws RandomException
     */
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

        // 30 participations aléatoires
        for ($i = 0; $i < 30; ++$i) {
            ParticipationFactory::createOne([
                'utilisateur' => UserFactory::random(),
                'evenement' => EvenementFactory::random(),
                'dateInscription' => new \DateTime('-'.random_int(1, 20).' days'),
                'dateDesinscription' => null,
                'statut' => 'INSCRIT',
                'remarque' => null,
            ]);
        }

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
