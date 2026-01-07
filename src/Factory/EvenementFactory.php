<?php

namespace App\Factory;

use App\Entity\Evenement;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Evenement>
 */
final class EvenementFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Evenement::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     *
     * @throws \DateMalformedStringException
     */
    protected function defaults(): array|callable
    {
        $dateDebut = self::faker()->dateTimeBetween('+1 days', '+90 days');
        $dateFin = (clone $dateDebut);
        $dateFin->modify('+'.self::faker()->numberBetween(1, 6).' hours');

        $dateLimite = (clone $dateDebut);
        $dateLimite->modify('-'.self::faker()->numberBetween(1, 7).' days');

        $dateCrea = self::faker()->dateTimeBetween('-30 days', 'now');
        $dateModif = (clone $dateCrea);
        $dateModif->modify('+'.self::faker()->numberBetween(0, 10).' days');

        $lieux = [
            'Campus Croix-Rouge, Reims',
            'Campus Moulin Housse, Reims',
            'Centre-ville, Reims',
            'Parc Léo Lagrange, Reims',
            'Parc de Champagne, Reims',
            'Parc Pierre Mendès, Reims',
            'Musée des Beaux-Arts, Reims',
            'Gare de Reims'];

        $titres = [
            'Afterwork international',
            'Soirée d’intégration internationale',
            'Visite des Caves Pommery',
            'Sortie musée',
            'Session sport au parc',
            'Tournoi de foot amical',
            'Conférence : vivre à Reims',
            'Atelier CV & alternance',
        ];

        $descriptions = [
            'Rencontre conviviale pour échanger, rencontrer de nouveaux étudiants et découvrir la vie à Reims.',
            'Sortie en petit groupe suivie d’un café pour apprendre à se connaître.',
            'Activité sportive accessible à tous : footing léger + étirements.',
            'Moment d’échange autour de la culture, avec visite et discussion.',
            'Conférence et Q/R pour faciliter l’intégration des nouveaux arrivants.',
        ];

        return [
            'capacite' => self::faker()->numberBetween(5, 50),
            'dateCrea' => $dateCrea,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'dateLimiteInscr' => $dateLimite,
            'dateModif' => $dateModif,
            'description' => self::faker()->randomElement($descriptions),
            'lieu' => self::faker()->randomElement($lieux),
            'statut' => 'OUVERT',
            'titre' => self::faker()->randomElement($titres),
            'categorie' => self::faker()->randomElement(['Intégration', 'Culture', 'Sport', 'Loisirs', 'Conférence']),
            'createur' => UserFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (Evenement $evenement): void {
                $scenario = self::faker()->randomElement(['VIDE', 'MOYEN', 'PLEIN', 'ANNULE']);
                $capacite = $evenement->getCapacite();

                if ('ANNULE' === $scenario) {
                    $evenement->setStatut('ANNULE');

                    return; // On s'arrête là
                }

                $nbParticipants = 0;

                if ('VIDE' === $scenario) {
                    // Entre 0 et 10% de remplissage
                    $nbParticipants = self::faker()->numberBetween(0, (int) ceil($capacite * 0.1));
                } elseif ('MOYEN' === $scenario) {
                    // Entre 20% et 80% de remplissage
                    $nbParticipants = self::faker()->numberBetween((int) ceil($capacite * 0.2), (int) ceil($capacite * 0.8));
                } elseif ('PLEIN' === $scenario) {
                    // EXACTEMENT la capacité max
                    $nbParticipants = $capacite;
                }

                // SÉCURITÉ SUPPLÉMENTAIRE : On s'assure que mathématiquement on ne dépasse jamais
                $nbParticipants = min($nbParticipants, $capacite);

                if ($nbParticipants > 0) {
                    ParticipationFactory::createMany($nbParticipants, [
                        'evenement' => $evenement,
                        'utilisateur' => UserFactory::random(), // Des utilisateurs au hasard s'inscrivent
                        'dateInscription' => self::faker()->dateTimeBetween('-10 days', 'now'),
                        'statut' => 'INSCRIT',
                    ]);
                }

                if ($nbParticipants >= $capacite) {
                    $evenement->setStatut('COMPLET');
                } else {
                    $evenement->setStatut('OUVERT');
                }
            })
        ;
    }
}
