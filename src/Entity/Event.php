<?php

namespace App\Entity;

use App\Enum\EventStatus;
use App\Enum\EventUserStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $longDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDateTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endDateTime = null;

    #[ORM\ManyToMany(targetEntity: Image::class, inversedBy: 'events', cascade: ["persist"])]
    private Collection $images;

    /**
     * @var Collection<int, EventUser>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventUser::class, orphanRemoval: true, cascade: ["persist"])]
    private Collection $eventUsers;

    #[ORM\Column(type: Types::STRING, enumType: EventStatus::class)]
    private EventStatus $status = EventStatus::Draft;
    
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->eventUsers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeImmutable $startDateTime): static
    {
        $this->startDateTime = $startDateTime;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeImmutable
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeImmutable $endDateTime): static
    {
        $this->endDateTime = $endDateTime;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getStatus(): EventStatus
    {
        return $this->status;
    }

    public function setStatus(EventStatus $status): static
    {
        $this->status = $status;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->addEvent($this);
        }
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            $image->removeEvent($this);
        }
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    /**
     * @return Collection<int, EventUser>
     */
    public function getEventUsers(): Collection
    {
        return $this->eventUsers;
    }

    public function addEventUser(EventUser $eventUser): static
    {
        if (!$this->eventUsers->contains($eventUser)) {
            $this->eventUsers->add($eventUser);
            $eventUser->setEvent($this);
        }

        return $this;
    }

    public function removeEventUser(EventUser $eventUser): static
    {
        if ($this->eventUsers->removeElement($eventUser)) {
            // set the owning side to null (unless already changed)
            if ($eventUser->getEvent() === $this) {
                $eventUser->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * Vérifie si l'événement est visible pour le public
     */
    public function isVisible(): bool
    {
        return $this->status === EventStatus::Published;
    }

    /**
     * Méthodes utilitaires pour gérer les relations avec les utilisateurs
     */
    public function addUserWithStatus(User $user, EventUserStatus $status): static
    {
        $eventUser = new EventUser();
        $eventUser->setUser($user);
        $eventUser->setEvent($this);
        $eventUser->setStatus($status);
        $this->addEventUser($eventUser);

        return $this;
    }
    
    /**
     * Ajoute un utilisateur comme organisateur de l'événement
     */
    public function addOrganizer(User $user): static
    {
        return $this->addUserWithStatus($user, EventUserStatus::ORGANIZER);
    }

    /**
     * Ajoute un utilisateur comme participant à l'événement
     */
    public function addParticipant(User $user): static
    {
        return $this->addUserWithStatus($user, EventUserStatus::PARTICIPANT);
    }

    /**
     * Ajoute un utilisateur comme intéressé par l'événement
     */
    public function addInterested(User $user): static
    {
        return $this->addUserWithStatus($user, EventUserStatus::INTERESTED);
    }

    /**
     * Invite un utilisateur à l'événement
     */
    public function inviteUser(User $user): static
    {
        return $this->addUserWithStatus($user, EventUserStatus::INVITED);
    }

    /**
     * Récupère toutes les relations EventUser avec un statut spécifique
     */
    private function getEventUsersByStatus(EventUserStatus $status): Collection
    {
        return $this->eventUsers->filter(fn (EventUser $eventUser) => $eventUser->getStatus() === $status);
    }

    /**
     * Récupère tous les utilisateurs avec un statut spécifique
     */
    private function getUsersByStatus(EventUserStatus $status): Collection
    {
        return $this->getEventUsersByStatus($status)
            ->map(fn (EventUser $eventUser) => $eventUser->getUser());
    }

    /**
     * @return Collection<int, User>
     */
    public function getOrganizers(): Collection
    {
        return $this->getUsersByStatus(EventUserStatus::ORGANIZER);
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->getUsersByStatus(EventUserStatus::PARTICIPANT);
    }

    /**
     * @return Collection<int, User>
     */
    public function getInterested(): Collection
    {
        return $this->getUsersByStatus(EventUserStatus::INTERESTED);
    }

    /**
     * @return Collection<int, User>
     */
    public function getInvited(): Collection
    {
        return $this->getUsersByStatus(EventUserStatus::INVITED);
    }

    /**
     * Vérifie si un utilisateur a un statut spécifique pour cet événement
     */
    public function hasUserWithStatus(User $user, EventUserStatus $status): bool
    {
        foreach ($this->eventUsers as $eventUser) {
            if ($eventUser->getUser() === $user && $eventUser->getStatus() === $status) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Récupère le statut d'un utilisateur pour cet événement
     */
    public function getUserStatus(User $user): ?EventUserStatus
    {
        foreach ($this->eventUsers as $eventUser) {
            if ($eventUser->getUser() === $user) {
                return $eventUser->getStatus();
            }
        }
        
        return null;
    }

    /**
     * Modifie le statut d'un utilisateur pour cet événement
     */
    public function setUserStatus(User $user, EventUserStatus $status): static
    {
        foreach ($this->eventUsers as $eventUser) {
            if ($eventUser->getUser() === $user) {
                $eventUser->setStatus($status);
                return $this;
            }
        }
        
        // Si la relation n'existe pas encore, la créer
        return $this->addUserWithStatus($user, $status);
    }
}
