<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Factory\ConversationFactory;
use App\Factory\MessageFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::find(['email' => 'root@example.com']);
        $user = UserFactory::find(['email' => 'user@example.com']);

        //  démo (récupérée depuis ConversationFixtures)
        $conversationDemo = $this->getReference('conversation_demo', Conversation::class);

        // Messages démo
        MessageFactory::createOne([
            'conversation' => $conversationDemo,
            'utilisateur' => $admin,
            'contenu' => 'Salut, bienvenue sur Buddy System.',
            'dateEnvoi' => new \DateTime('-7 days'),
            'dateLecture' => new \DateTime('-7 days'),
        ]);

        MessageFactory::createOne([
            'conversation' => $conversationDemo,
            'utilisateur' => $user,
            'contenu' => 'Merci ! Je suis en échange à l’URCA, je cherche des gens pour découvrir Reims.',
            'dateEnvoi' => new \DateTime('-6 days'),
            'dateLecture' => new \DateTime('-6 days'),
        ]);

        MessageFactory::createOne([
            'conversation' => $conversationDemo,
            'utilisateur' => $admin,
            'contenu' => 'Parfait, regarde aussi les événements, tu peux t’inscrire.',
            'dateEnvoi' => new \DateTime('-5 days'),
            'dateLecture' => null,
        ]);

        MessageFactory::createMany(80, fn () => ['conversation' => ConversationFactory::random()]);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ConversationFixtures::class,
        ];
    }
}
