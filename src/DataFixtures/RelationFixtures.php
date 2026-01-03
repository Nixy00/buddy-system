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

        RelationFactory::createMany(25, function () {
            $envoyeur = UserFactory::random();
            $receveur = UserFactory::random();

            while ($receveur->getId() === $envoyeur->getId()) {
                $receveur = UserFactory::random();
            }

            return ['envoyeur' => $envoyeur, 'receveur' => $receveur];
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
