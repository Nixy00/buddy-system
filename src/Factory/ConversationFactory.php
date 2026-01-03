<?php

namespace App\Factory;

use App\Entity\Conversation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Conversation>
 */
final class ConversationFactory extends PersistentProxyObjectFactory
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
        return Conversation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dateCrea' => self::faker()->dateTimeBetween('-60 days', 'now'),
            'initiateur' => UserFactory::new(),
            'interlocuteur' => UserFactory::new(),
            'statut' => self::faker()->randomElement(['OUVERTE', 'FERMEE', 'ARCHIVEE']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Conversation $conversation): void {
            while ($conversation->getInitiateur() && $conversation->getInterlocuteur()
                && $conversation->getInitiateur()->getId() === $conversation->getInterlocuteur()->getId()
            ) {
                $conversation->setInterlocuteur(UserFactory::new()->create()->_real());
            }
        });
    }
}
