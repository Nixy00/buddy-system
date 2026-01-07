<?php

namespace App\DataFixtures;

use App\Factory\EvenementFactory;
use App\Factory\ParticipationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EvenementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        EvenementFactory::createOne([
            'titre' => 'Afterwork international',
            'description' => 'Rencontre conviviale pour échanger et faire de nouvelles connaissances.',
            'lieu' => 'Campus Croix-Rouge, Reims',
            'dateDebut' => new \DateTime('2026-02-10 18:30:00'),
            'dateFin' => new \DateTime('2026-02-10 21:30:00'),
            'dateLimiteInscr' => new \DateTime('2026-02-09 23:59:59'),
            'capacite' => 40,
            'statut' => 'OUVERT',
            'dateCrea' => new \DateTime('2026-01-15 10:00:00'),
            'dateModif' => new \DateTime('2026-01-15 10:00:00'),
            'categorie' => 'Loisirs',
            'createur' => UserFactory::random(),
        ]);

        EvenementFactory::createOne([
            'titre' => 'Sortie musée',
            'description' => 'Visite en groupe suivie d’un café.',
            'lieu' => 'Musée des Beaux-Arts, Reims',
            'dateDebut' => new \DateTime('2026-03-01 14:00:00'),
            'dateFin' => new \DateTime('2026-03-01 17:00:00'),
            'dateLimiteInscr' => new \DateTime('2026-02-28 23:59:59'),
            'capacite' => 20,
            'statut' => 'OUVERT',
            'dateCrea' => new \DateTime('2026-01-20 09:00:00'),
            'dateModif' => new \DateTime('2026-01-20 09:00:00'),
            'categorie' => 'Culture',
            'createur' => UserFactory::random(),
        ]);

        EvenementFactory::createOne([
            'titre' => 'Session sport',
            'description' => 'Footing léger + étirements, ouvert à tous.',
            'lieu' => 'Parc Léo Lagrange, Reims',
            'dateDebut' => new \DateTime('2026-02-22 10:00:00'),
            'dateFin' => new \DateTime('2026-02-22 11:30:00'),
            'dateLimiteInscr' => new \DateTime('2026-02-21 23:59:59'),
            'capacite' => 25,
            'statut' => 'OUVERT',
            'dateCrea' => new \DateTime('2026-01-18 12:00:00'),
            'dateModif' => new \DateTime('2026-01-18 12:00:00'),
            'categorie' => 'Sport',
            'createur' => UserFactory::random(),
        ]);

        EvenementFactory::createOne([
            'titre' => 'Concert annulé',
            'description' => 'Cet événement a été annulé pour des raisons organisationnelles.',
            'lieu' => 'Salle de spectacle, Reims',
            'dateDebut' => new \DateTime('2026-02-15 20:00:00'),
            'dateFin' => new \DateTime('2026-02-15 23:00:00'),
            'dateLimiteInscr' => new \DateTime('2026-02-14 23:59:59'),
            'capacite' => 40,
            'statut' => 'ANNULE',
            'dateCrea' => new \DateTime('2026-01-10 10:00:00'),
            'dateModif' => new \DateTime('2026-01-25 15:30:00'),
            'categorie' => 'Culture',
            'createur' => UserFactory::random(),
        ]);

        $events = EvenementFactory::createMany(30);

        // remplissage intelligent
        foreach ($events as $event) {
            $scenario = Factory::create()->randomElement(['MOYEN', 'PLEIN', 'VIDE']);

            if (random_int(0, 10) > 9) {
                $event->setStatut('ANNULE');
                continue;
            }

            $capacite = $event->getCapacite();
            $nbParticipants = 0;

            if ('VIDE' === $scenario) {
                $nbParticipants = random_int(0, (int) ceil($capacite * 0.1));
            } elseif ('MOYEN' === $scenario) {
                $nbParticipants = random_int((int) ceil($capacite * 0.3), (int) ceil($capacite * 0.8));
            } elseif ('PLEIN' === $scenario) {
                $nbParticipants = $capacite;
            }

            if ($nbParticipants > 0) {
                ParticipationFactory::createMany($nbParticipants, fn () => [
                    'evenement' => $event,
                    'utilisateur' => UserFactory::random(),
                    'statut' => 'INSCRIT',
                ]);
            }

            if ($nbParticipants >= $capacite) {
                $event->setStatut('COMPLET');
            }
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
