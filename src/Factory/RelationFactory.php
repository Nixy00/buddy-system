<?php

namespace App\Factory;

use App\Entity\Relation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Relation>
 */
final class RelationFactory extends PersistentProxyObjectFactory
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
        return Relation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateDemande' => self::faker()->dateTimeBetween('-30 days', 'now'),
            'envoyeur' => UserFactory::new(),
            'receveur' => UserFactory::new(),
            'statut' => self::faker()->randomElement(['EN_ATTENTE', 'ACCEPTEE', 'REFUSEE']),
            'dateReponse' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Relation $relation): void {
            while ($relation->getEnvoyeur() && $relation->getReceveur()
                && $relation->getEnvoyeur()->getId() === $relation->getReceveur()->getId()
            ) {
                $relation->setReceveur(UserFactory::new()->create()->_real());
            }

            if ('EN_ATTENTE' !== $relation->getStatut()) {
                $dateDemande = $relation->getDateDemande() ?? new \DateTime('-7 days');
                $dateReponse = (clone $dateDemande);
                $dateReponse->modify('+'.self::faker()->numberBetween(1, 10).' days');
                $relation->setDateReponse($dateReponse);
            }
        });
    }
}
