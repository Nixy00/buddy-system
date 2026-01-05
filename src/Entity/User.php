<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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

    /**
     * @var Collection<int, Organisation>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Organisation::class, orphanRemoval: true)]
    private Collection $organisations;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\OneToMany(mappedBy: 'initiateur', targetEntity: Conversation::class, orphanRemoval: true)]
    private Collection $conversationsInitiees;

    /**
     * @var Collection<int, Conversation>
     */
    #[ORM\OneToMany(mappedBy: 'interlocuteur', targetEntity: Conversation::class, orphanRemoval: true)]
    private Collection $conversationsRecues;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int, Signalement>
     */
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Signalement::class, orphanRemoval: true)]
    private Collection $signalements;

    /**
     * @var Collection<int, Relation>
     */
    #[ORM\OneToMany(mappedBy: 'envoyeur', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationsEnvoyees;

    /**
     * @var Collection<int, Relation>
     */
    #[ORM\OneToMany(mappedBy: 'receveur', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $relationsRecues;

    public function __construct()
    {
        $this->possessions = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->organisations = new ArrayCollection();
        $this->conversationsInitiees = new ArrayCollection();
        $this->conversationsRecues = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->signalements = new ArrayCollection();
        $this->relationsEnvoyees = new ArrayCollection();
        $this->relationsRecues = new ArrayCollection();
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
            $organisation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): static
    {
        if ($this->organisations->removeElement($organisation)) {
            // set the owning side to null (unless already changed)
            if ($organisation->getUtilisateur() === $this) {
                $organisation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationsInitiees(): Collection
    {
        return $this->conversationsInitiees;
    }

    public function addConversationsInitiee(Conversation $conversationsInitiee): static
    {
        if (!$this->conversationsInitiees->contains($conversationsInitiee)) {
            $this->conversationsInitiees->add($conversationsInitiee);
            $conversationsInitiee->setInitiateur($this);
        }

        return $this;
    }

    public function removeConversationsInitiee(Conversation $conversationsInitiee): static
    {
        if ($this->conversationsInitiees->removeElement($conversationsInitiee)) {
            // set the owning side to null (unless already changed)
            if ($conversationsInitiee->getInitiateur() === $this) {
                $conversationsInitiee->setInitiateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationsRecues(): Collection
    {
        return $this->conversationsRecues;
    }

    public function addConversationsRecue(Conversation $conversationsRecue): static
    {
        if (!$this->conversationsRecues->contains($conversationsRecue)) {
            $this->conversationsRecues->add($conversationsRecue);
            $conversationsRecue->setInterlocuteur($this);
        }

        return $this;
    }

    public function removeConversationsRecue(Conversation $conversationsRecue): static
    {
        if ($this->conversationsRecues->removeElement($conversationsRecue)) {
            // set the owning side to null (unless already changed)
            if ($conversationsRecue->getInterlocuteur() === $this) {
                $conversationsRecue->setInterlocuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUtilisateur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUtilisateur() === $this) {
                $message->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Signalement>
     */
    public function getSignalements(): Collection
    {
        return $this->signalements;
    }

    public function addSignalement(Signalement $signalement): static
    {
        if (!$this->signalements->contains($signalement)) {
            $this->signalements->add($signalement);
            $signalement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeSignalement(Signalement $signalement): static
    {
        if ($this->signalements->removeElement($signalement)) {
            // set the owning side to null (unless already changed)
            if ($signalement->getUtilisateur() === $this) {
                $signalement->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelationsEnvoyees(): Collection
    {
        return $this->relationsEnvoyees;
    }

    public function addRelationsEnvoyee(Relation $relationsEnvoyee): static
    {
        if (!$this->relationsEnvoyees->contains($relationsEnvoyee)) {
            $this->relationsEnvoyees->add($relationsEnvoyee);
            $relationsEnvoyee->setEnvoyeur($this);
        }

        return $this;
    }

    public function removeRelationsEnvoyee(Relation $relationsEnvoyee): static
    {
        if ($this->relationsEnvoyees->removeElement($relationsEnvoyee)) {
            // set the owning side to null (unless already changed)
            if ($relationsEnvoyee->getEnvoyeur() === $this) {
                $relationsEnvoyee->setEnvoyeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelationsRecues(): Collection
    {
        return $this->relationsRecues;
    }

    public function addRelationsRecue(Relation $relationsRecue): static
    {
        if (!$this->relationsRecues->contains($relationsRecue)) {
            $this->relationsRecues->add($relationsRecue);
            $relationsRecue->setReceveur($this);
        }

        return $this;
    }

    public function removeRelationsRecue(Relation $relationsRecue): static
    {
        if ($this->relationsRecues->removeElement($relationsRecue)) {
            // set the owning side to null (unless already changed)
            if ($relationsRecue->getReceveur() === $this) {
                $relationsRecue->setReceveur(null);
            }
        }

        return $this;
    }

    /**
     * identifiant visuel qui représente cet utilisateur.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
