<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Factory\MessageFactory;
use App\Factory\SignalementFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SignalementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::find(['email' => 'root@example.com']);
        $user = UserFactory::find(['email' => 'user@example.com']);

        // Conversation démo (créée dans ConversationFixtures)
        $conversationDemo = $this->getReference('conversation_demo', Conversation::class);

        // On récupère un message de cette conversation (créé dans MessageFixtures)
        $messageDemo = MessageFactory::find(['conversation' => $conversationDemo]);

        // Signalements démo (fixes)
        SignalementFactory::createOne([
            'message' => $messageDemo,
            'utilisateur' => $user,
            'motif' => 'Spam',
            'statut' => 'EN_ATTENTE',
            'dateSignalement' => new \DateTime('-2 days'),
            'commentaireModeration' => null,
        ]);

        SignalementFactory::createOne([
            'message' => $messageDemo,
            'utilisateur' => $admin,
            'motif' => 'Contenu inapproprié',
            'statut' => 'TRAITE',
            'dateSignalement' => new \DateTime('-1 days'),
            'commentaireModeration' => 'Signalement traité : aucun problème détecté.',
        ]);

        SignalementFactory::createMany(30, fn () => [
            'message' => MessageFactory::random(),
            'utilisateur' => UserFactory::random(),
        ]);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ConversationFixtures::class,
            MessageFixtures::class,
        ];
    }
}
