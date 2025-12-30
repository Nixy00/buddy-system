<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_conversation', columns: ['initiateur_id', 'interlocuteur_id'])]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $initiateur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $interlocuteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_crea = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    public function __construct()
    {
        $this->date_crea = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitiateur(): ?Utilisateur
    {
        return $this->initiateur;
    }

    public function setInitiateur(?Utilisateur $initiateur): static
    {
        $this->initiateur = $initiateur;
        return $this;
    }

    public function getInterlocuteur(): ?Utilisateur
    {
        return $this->interlocuteur;
    }

    public function setInterlocuteur(?Utilisateur $interlocuteur): static
    {
        $this->interlocuteur = $interlocuteur;
        return $this;
    }

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->date_crea;
    }

    public function setDateCrea(\DateTimeInterface $date_crea): static
    {
        $this->date_crea = $date_crea;
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
}
