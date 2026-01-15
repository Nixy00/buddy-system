<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true)]
    #[ORM\OrderBy(['dateEnvoi' => 'ASC'])]
    private Collection $messages;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'conversationsSupprimees')]
    #[ORM\JoinTable(name: 'conversation_suppressions')]
    private Collection $supprimePar;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'conversationsArchivees')]
    #[ORM\JoinTable(name: 'conversation_archives')]
    private Collection $archivePar;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->supprimePar = new ArrayCollection();
        $this->archivePar = new ArrayCollection();
    }

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
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSupprimePar(): Collection
    {
        return $this->supprimePar;
    }

    public function addSupprimePar(User $supprimePar): static
    {
        if (!$this->supprimePar->contains($supprimePar)) {
            $this->supprimePar->add($supprimePar);
        }

        return $this;
    }

    public function removeSupprimePar(User $supprimePar): static
    {
        $this->supprimePar->removeElement($supprimePar);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getArchivePar(): Collection
    {
        return $this->archivePar;
    }

    public function addArchivePar(User $archivePar): static
    {
        if (!$this->archivePar->contains($archivePar)) {
            $this->archivePar->add($archivePar);
        }

        return $this;
    }

    public function removeArchivePar(User $archivePar): static
    {
        $this->archivePar->removeElement($archivePar);

        return $this;
    }
}
