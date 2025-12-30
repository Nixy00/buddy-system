<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?\DateTime $dateDemande = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateReponse = null;

    #[ORM\ManyToOne(inversedBy: 'relationsEnvoyees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $envoyeur = null;

    #[ORM\ManyToOne(inversedBy: 'relationsRecues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receveur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDemande(): ?\DateTime
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTime $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateReponse(): ?\DateTime
    {
        return $this->dateReponse;
    }

    public function setDateReponse(?\DateTime $dateReponse): static
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getEnvoyeur(): ?User
    {
        return $this->envoyeur;
    }

    public function setEnvoyeur(?User $envoyeur): static
    {
        $this->envoyeur = $envoyeur;

        return $this;
    }

    public function getReceveur(): ?User
    {
        return $this->receveur;
    }

    public function setReceveur(?User $receveur): static
    {
        $this->receveur = $receveur;

        return $this;
    }
}
