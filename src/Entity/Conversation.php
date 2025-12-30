<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateCrea = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'conversationsInitiees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $initiateur = null;

    #[ORM\ManyToOne(inversedBy: 'conversationsRecues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $interlocuteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCrea(): ?\DateTime
    {
        return $this->dateCrea;
    }

    public function setDateCrea(\DateTime $dateCrea): static
    {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getInitiateur(): ?User
    {
        return $this->initiateur;
    }

    public function setInitiateur(?User $initiateur): static
    {
        $this->initiateur = $initiateur;

        return $this;
    }

    public function getInterlocuteur(): ?User
    {
        return $this->interlocuteur;
    }

    public function setInterlocuteur(?User $interlocuteur): static
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }
}
