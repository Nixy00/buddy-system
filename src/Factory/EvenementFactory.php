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
     */
    protected function defaults(): array|callable
    {
        return [
            'capacite' => self::faker()->randomNumber(),
            'dateCrea' => self::faker()->dateTime(),
            'dateDebut' => self::faker()->dateTime(),
            'dateFin' => self::faker()->dateTime(),
            'dateLimiteInscr' => self::faker()->dateTime(),
            'dateModif' => self::faker()->dateTime(),
            'description' => self::faker()->text(),
            'lieu' => self::faker()->text(150),
            'statut' => self::faker()->text(20),
            'titre' => self::faker()->text(100),
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
