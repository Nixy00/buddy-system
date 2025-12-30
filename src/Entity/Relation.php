<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_relation', columns: ['envoyeur_id', 'receveur_id'])]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $envoyeur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $receveur = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_reponse = null;

    public function __construct()
    {
        $this->date_demande = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnvoyeur(): ?Utilisateur
    {
        return $this->envoyeur;
    }

    public function setEnvoyeur(?Utilisateur $envoyeur): static
    {
        $this->envoyeur = $envoyeur;
        return $this;
    }

    public function getReceveur(): ?Utilisateur
    {
        return $this->receveur;
    }

    public function setReceveur(?Utilisateur $receveur): static
    {
        $this->receveur = $receveur;
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

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): static
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->date_reponse;
    }

    public function setDateReponse(?\DateTimeInterface $date_reponse): static
    {
        $this->date_reponse = $date_reponse;
        return $this;
    }
}
