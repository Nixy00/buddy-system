<?php

namespace App\Twig;

use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\RelationRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavbarExtension extends AbstractExtension
{
    private MessageRepository $messageRepository;
    private RelationRepository $relationRepository;

    public function __construct(MessageRepository $messageRepository, RelationRepository $relationRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->relationRepository = $relationRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_global_notif_count', [$this, 'getGlobalNotifCount']),
        ];
    }

    public function getGlobalNotifCount(User $user): int
    {
        $messages = $this->messageRepository->countUnreadMessagesByUser($user);
        $demandes = $this->relationRepository->countDemandReceivedByUser($user);

        return $messages + $demandes;
    }
}
