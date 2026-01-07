<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    #[Assert\Length(min: 3, minMessage: 'Le titre doit faire au moins {{ limit }} caractères.')]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    private ?string $description = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le lieu est obligatoire.')]
    private ?string $lieu = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La date de début est obligatoire.')]
    #[Assert\GreaterThanOrEqual('today', message: 'La date de début ne peut pas être dans le passé.')]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La date de fin est obligatoire.')]
    #[Assert\GreaterThan(propertyPath: 'dateDebut', message: 'La date de fin doit être postérieure à la date de début.')]
    private ?\DateTime $dateFin = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date limite d'inscription est obligatoire.")]
    #[Assert\GreaterThan('today', message: "La date limite d'inscription doit être future.")]
    private ?\DateTime $dateLimiteInscr = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Le nombre de places est obligatoire.')]
    #[Assert\Positive(message: 'Le nombre de places doit être supérieur à 0.')]
    private ?int $capacite = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?\DateTime $dateCrea = null;

    #[ORM\Column]
    private ?\DateTime $dateModif = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Veuillez choisir une catégorie.')]
    private ?string $categorie = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createur = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Participation::class, orphanRemoval: true)]
    private Collection $participations;

    /**
     * @var Collection<int, Organisation>
     */
    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Organisation::class, orphanRemoval: true)]
    private Collection $organisations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->organisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateLimiteInscr(): ?\DateTime
    {
        return $this->dateLimiteInscr;
    }

    public function setDateLimiteInscr(\DateTime $dateLimiteInscr): static
    {
        $this->dateLimiteInscr = $dateLimiteInscr;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

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

    public function getDateCrea(): ?\DateTime
    {
        return $this->dateCrea;
    }

    public function setDateCrea(\DateTime $dateCrea): static
    {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    public function getDateModif(): ?\DateTime
    {
        return $this->dateModif;
    }

    public function setDateModif(\DateTime $dateModif): static
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    public function setCreateur(?User $createur): static
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setEvenement($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            if ($participation->getEvenement() === $this) {
                $participation->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Organisation>
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): static
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations->add($organisation);
            $organisation->setEvenement($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): static
    {
        if ($this->organisations->removeElement($organisation)) {
            if ($organisation->getEvenement() === $this) {
                $organisation->setEvenement(null);
            }
        }

        return $this;
    }
}
