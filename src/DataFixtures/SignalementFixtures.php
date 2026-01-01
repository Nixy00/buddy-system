<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Factory\MessageFactory;
use App\Factory\SignalementFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SignalementFixtures extends Fixture
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

        // Signalements random
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 30; ++$i) {
            $message = MessageFactory::random();

            $statut = $faker->randomElement(['EN_ATTENTE', 'TRAITE', 'REFUSE']);
            $commentaireModeration = null;

            if ('EN_ATTENTE' !== $statut) {
                $commentaireModeration = $faker->sentence(10);
            }

            SignalementFactory::createOne([
                'message' => $message,
                'utilisateur' => $faker->randomElement([$admin, $user]),
                'motif' => $faker->randomElement(['Spam', 'Harcèlement', 'Contenu inapproprié', 'Arnaque']),
                'statut' => $statut,
                'dateSignalement' => new \DateTime('-'.random_int(1, 20).' days'),
                'commentaireModeration' => $commentaireModeration,
            ]);
        }

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
