<?php

namespace App\DataFixtures;

use App\Factory\ConversationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class ConversationFixtures extends Fixture
{
    /**
     * @throws \DateMalformedStringException
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::find(['email' => 'root@example.com']);
        $user = UserFactory::find(['email' => 'user@example.com']);

        // démo : admin initiateur d'une conversation avec user
        $convDemo = ConversationFactory::createOne([
            'dateCrea' => new \DateTime('-7 days'),
            'statut' => 'OUVERTE',
            'initiateur' => $admin,
            'interlocuteur' => $user,
        ]);

        $this->addReference('conversation_demo', $convDemo->object());

        // 15 conversations aléatoires entre users
        for ($i = 1; $i <= 15; ++$i) {
            $initiateur = UserFactory::random();
            $interlocuteur = UserFactory::random();

            // éviter une conversation avec soi-même
            while ($interlocuteur->getId() === $initiateur->getId()) {
                $interlocuteur = UserFactory::random();
            }

            $conv = ConversationFactory::createOne([
                'dateCrea' => new \DateTime('-'.random_int(1, 30).' days'),
                'statut' => 'OUVERTE',
                'initiateur' => $initiateur,
                'interlocuteur' => $interlocuteur,
            ]);

            $this->addReference('conversation_'.$i, $conv->object());
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
