<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $mdpHache = null;

    #[ORM\Column(length: 190)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTime $dateCrea = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDerniereConnexion = null;

    #[ORM\OneToOne(mappedBy: 'utilisateur', cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    /**
     * @var Collection<int, Possession>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Possession::class, orphanRemoval: true)]
    private Collection $possessions;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Participation::class, orphanRemoval: true)]
    private Collection $participations;

    public function __construct()
    {
        $this->possessions = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getMdpHache(): ?string
    {
        return $this->mdpHache;
    }

    public function setMdpHache(string $mdpHache): static
    {
        $this->mdpHache = $mdpHache;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getDateDerniereConnexion(): ?\DateTime
    {
        return $this->dateDerniereConnexion;
    }

    public function setDateDerniereConnexion(?\DateTime $dateDerniereConnexion): static
    {
        $this->dateDerniereConnexion = $dateDerniereConnexion;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(Profil $profil): static
    {
        // set the owning side of the relation if necessary
        if ($profil->getUtilisateur() !== $this) {
            $profil->setUtilisateur($this);
        }

        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Possession>
     */
    public function getPossessions(): Collection
    {
        return $this->possessions;
    }

    public function addPossession(Possession $possession): static
    {
        if (!$this->possessions->contains($possession)) {
            $this->possessions->add($possession);
            $possession->setUtilisateur($this);
        }

        return $this;
    }

    public function removePossession(Possession $possession): static
    {
        if ($this->possessions->removeElement($possession)) {
            // set the owning side to null (unless already changed)
            if ($possession->getUtilisateur() === $this) {
                $possession->setUtilisateur(null);
            }
        }

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
            $participation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUtilisateur() === $this) {
                $participation->setUtilisateur(null);
            }
        }

        return $this;
    }
}
