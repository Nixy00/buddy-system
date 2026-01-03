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

        return [
            'capacite' => self::faker()->numberBetween(10, 150),
            'dateCrea' => $dateCrea,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'dateLimiteInscr' => $dateLimite,
            'dateModif' => $dateModif,
            'description' => self::faker()->paragraph(),
            'lieu' => self::faker()->randomElement($lieux),
            'statut' => self::faker()->randomElement(['OUVERT', 'COMPLET', 'ANNULE']),
            'titre' => self::faker()->sentence(4),
            'categorie' => self::faker()->randomElement(['Intégration', 'Culture', 'Sport', 'Loisirs', 'Conférence']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Evenement $evenement): void {})
        ;
    }
}
