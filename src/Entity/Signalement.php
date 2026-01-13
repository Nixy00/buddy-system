<?php

namespace App\Entity;

use App\Repository\SignalementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignalementRepository::class)]
class Signalement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column]
    private ?\DateTime $dateSignalement = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaireModeration = null;

    #[ORM\ManyToOne(inversedBy: 'signalements')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Message $message = null;

    #[ORM\ManyToOne(inversedBy: 'signalements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $reportedUser = null;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Evenement $reportedEvent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateSignalement(): ?\DateTime
    {
        return $this->dateSignalement;
    }

    public function setDateSignalement(\DateTime $dateSignalement): static
    {
        $this->dateSignalement = $dateSignalement;

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

    public function getCommentaireModeration(): ?string
    {
        return $this->commentaireModeration;
    }

    public function setCommentaireModeration(?string $commentaireModeration): static
    {
        $this->commentaireModeration = $commentaireModeration;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getReportedUser(): ?User
    {
        return $this->reportedUser;
    }

    public function setReportedUser(?User $reportedUser): static
    {
        $this->reportedUser = $reportedUser;

        return $this;
    }

    public function getReportedEvent(): ?Evenement
    {
        return $this->reportedEvent;
    }

    public function setReportedEvent(?Evenement $reportedEvent): static
    {
        $this->reportedEvent = $reportedEvent;

        return $this;
    }
}
