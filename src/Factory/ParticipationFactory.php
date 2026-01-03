<?php

namespace App\Factory;

use App\Entity\Participation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Participation>
 */
final class ParticipationFactory extends PersistentProxyObjectFactory
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
        return Participation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateInscription' => self::faker()->dateTimeBetween('-60 days', 'now'),
            'dateDesinscription' => null,
            'evenement' => EvenementFactory::new(),
            'statut' => self::faker()->randomElement(['INSCRIT', 'ANNULE']),
            'remarque' => self::faker()->boolean(25) ? self::faker()->sentence() : null,
            'utilisateur' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Participation $participation): void {
            if ('ANNULE' === $participation->getStatut()) {
                $dateInscription = $participation->getDateInscription() ?? new \DateTime('-10 days');
                $dateDesinscription = (clone $dateInscription);
                $dateDesinscription->modify('+'.self::faker()->numberBetween(1, 20).' days');
                $participation->setDateDesinscription($dateDesinscription);
            }
        });
    }
}
