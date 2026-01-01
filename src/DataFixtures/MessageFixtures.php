<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Factory\ConversationFactory;
use App\Factory\MessageFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class MessageFixtures extends Fixture
{
    /**
     * @throws \DateMalformedStringException
     * @throws RandomException
     */
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

        // 80 messages répartis sur des conversations random
        for ($i = 0; $i < 80; ++$i) {
            $conversation = ConversationFactory::random();

            // messages venant soit de l'initiateur soit de l'interloctueur
            $auteur = (0 === random_int(0, 1)) ? $conversation->getInitiateur() : $conversation->getInterlocuteur();

            $dateEnvoi = new \DateTime('-'.random_int(1, 30).' days');

            $dateLecture = null;
            if (random_int(0, 100) < 70) { // environ 70% des messages sont lues (et ont donc une date de lecture)
                $dateLecture = (clone $dateEnvoi);
                $dateLecture->modify('+'.random_int(1, 600).' minutes');
            }

            MessageFactory::createOne([
                'conversation' => $conversation,
                'utilisateur' => $auteur,
                'contenu' => 'Message #'.($i + 1),
                'dateEnvoi' => $dateEnvoi,
                'dateLecture' => $dateLecture,
            ]);
        }

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
