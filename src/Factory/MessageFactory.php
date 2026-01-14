<?php

namespace App\Factory;

use App\Entity\Message;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Message>
 */
final class MessageFactory extends PersistentProxyObjectFactory
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
        return Message::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'contenu' => self::faker()->paragraph(),
            'conversation' => ConversationFactory::new(),
            'dateEnvoi' => self::faker()->dateTimeBetween('-30 days', 'now'),
            'dateLecture' => null,
            'utilisateur' => UserFactory::new(),
            'estSupprime' => false,
            'dateModif' => null,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Message $message): void {
            $conversation = $message->getConversation();
            if ($conversation) {
                $initiateur = $conversation->getInitiateur();
                $interlocuteur = $conversation->getInterlocuteur();

                if ($initiateur && $interlocuteur) {
                    $message->setUtilisateur(self::faker()->randomElement([$initiateur, $interlocuteur]));
                }
            }

            // environ 70% lus
            if (self::faker()->boolean(70)) {
                $dateEnvoi = $message->getDateEnvoi() ?? new \DateTime('-7 days');
                $dateLecture = (clone $dateEnvoi);
                $dateLecture->modify('+'.self::faker()->numberBetween(1, 600).' minutes');
                $message->setDateLecture($dateLecture);
            }
        });
    }
}
