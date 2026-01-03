<?php

namespace App\Factory;

use App\Entity\Profil;
use Symfony\Component\Finder\Finder;
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
     * @throws \DateMalformedStringException
     */
    protected function defaults(): array|callable
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../assets/avatars');

        $avatars = [];
        foreach ($finder as $file) {
            $avatars[] = file_get_contents($file->getRealPath());
        }

        $dateCrea = self::faker()->dateTimeBetween('-2 years', 'now');
        $dateModif = (clone $dateCrea);
        $dateModif->modify('+'.self::faker()->numberBetween(0, 60).' days');

        return [
            'dateCrea' => $dateCrea,
            'dateModif' => $dateModif,
            'dateNaissance' => self::faker()->dateTimeBetween('-60 years', '-18 years'),
            'genre' => self::faker()->randomElement(['HOMME', 'FEMME']),
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'typeCompte' => self::faker()->randomElement(['ETUDIANT', 'BUDDY']),
            'avatar' => $avatars ? self::faker()->randomElement($avatars) : null,
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
