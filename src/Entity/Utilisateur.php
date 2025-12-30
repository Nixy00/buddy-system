<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 190, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp_hache = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date_crea = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_derniere_connexion = null;

    #[ORM\OneToOne(mappedBy: 'utilisateur', cascade: ['persist', 'remove'])]
    private ?Profil $profil = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Possession::class, orphanRemoval: true)]
    private Collection $possessions;

    public function __construct()
    {
        $this->date_crea = new \DateTime();
        $this->possessions = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->mdp_hache;
    }

    public function setPassword(string $password): static
    {
        $this->mdp_hache = $password;
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

    public function getDateDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->date_derniere_connexion;
    }

    public function setDateDerniereConnexion(?\DateTimeInterface $date_derniere_connexion): static
    {
        $this->date_derniere_connexion = $date_derniere_connexion;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // Géré via l'entité Possession/Role, mais pour Symfony Security on doit retourner un tableau
        // Pour l'instant on retourne ROLE_USER par défaut
        return ['ROLE_USER'];
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }
}
