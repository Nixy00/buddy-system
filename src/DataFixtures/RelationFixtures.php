<?php

namespace App\DataFixtures;

use App\Factory\RelationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class RelationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \DateMalformedStringException
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::find(['email' => 'root@example.com']);
        $user = UserFactory::find(['email' => 'user@example.com']);

        // démo d'une demande de relation d' admin envers user (en attente)
        RelationFactory::createOne([
            'envoyeur' => $admin,
            'receveur' => $user,
            'statut' => 'EN_ATTENTE',
            'dateDemande' => new \DateTime('-5 days'),
            'dateReponse' => null,
        ]);

        // 25 relations aléatoires
        for ($i = 0; $i < 25; ++$i) {
            $envoyeur = UserFactory::random();
            $receveur = UserFactory::random();

            // éviter une relation avec soi-même
            while ($receveur->getId() === $envoyeur->getId()) {
                $receveur = UserFactory::random();
            }

            $dateDemande = new \DateTime('-'.random_int(1, 30).' days');

            $statut = ['EN_ATTENTE', 'ACCEPTEE', 'REFUSEE'][random_int(0, 2)];

            $dateReponse = null;
            if ('EN_ATTENTE' !== $statut) {
                $dateReponse = (clone $dateDemande);
                $dateReponse->modify('+'.random_int(1, 10).' days');
            }

            RelationFactory::createOne([
                'envoyeur' => $envoyeur,
                'receveur' => $receveur,
                'statut' => $statut,
                'dateDemande' => $dateDemande,
                'dateReponse' => $dateReponse,
            ]);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
