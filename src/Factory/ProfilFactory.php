<?php

namespace App\Factory;

use App\Entity\Profil;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Profil>
 */
final class ProfilFactory extends PersistentProxyObjectFactory
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
        return Profil::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateCrea' => self::faker()->dateTime(),
            'dateModif' => self::faker()->dateTime(),
            'dateNaissance' => self::faker()->dateTime(),
            'genre' => self::faker()->text(30),
            'nom' => self::faker()->text(100),
            'prenom' => self::faker()->text(100),
            'typeCompte' => self::faker()->text(30),
            'utilisateur' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Profil $profil): void {})
        ;
    }
}
