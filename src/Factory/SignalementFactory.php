<?php

namespace App\Factory;

use App\Entity\Signalement;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Signalement>
 */
final class SignalementFactory extends PersistentProxyObjectFactory
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
        return Signalement::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateSignalement' => self::faker()->dateTimeBetween('-30 days', 'now'),
            'message' => MessageFactory::new(),
            'motif' => self::faker()->randomElement(['Spam', 'Harcèlement', 'Contenu inapproprié', 'Arnaque']),
            'statut' => self::faker()->randomElement(['EN_ATTENTE', 'TRAITE', 'REFUSE']),
            'commentaireModeration' => null,
            'utilisateur' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Signalement $signalement): void {
            if ('EN_ATTENTE' !== $signalement->getStatut()) {
                $signalement->setCommentaireModeration(
                    self::faker()->randomElement([
                        'Signalement traité par la modération.',
                        'Action effectuée conformément au règlement.',
                        'Signalement jugé non fondé.',
                    ])
                );
            }
        });
    }
}
