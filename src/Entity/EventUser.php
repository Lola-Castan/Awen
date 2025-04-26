<?php

namespace App\Entity;

use App\Enum\EventUserStatus;
use App\Repository\EventUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventUserRepository::class)]
#[ORM\Table(name: 'event_user')]
class EventUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'eventUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'string', enumType: EventUserStatus::class)]
    private EventUserStatus $status = EventUserStatus::INTERESTED;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $statusChangedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $participationConfirmedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $invitedAt = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $statusHistory = [];

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->statusChangedAt = new \DateTimeImmutable();
        $this->statusHistory = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): EventUserStatus
    {
        return $this->status;
    }

    public function setStatus(EventUserStatus $status): static
    {
        // Si le statut change, enregistrer l'historique et mettre à jour les timestamps
        if ($this->status !== $status) {
            $now = new \DateTimeImmutable();
            $this->statusChangedAt = $now;
            $this->updatedAt = $now;
            
            // Enregistrer le changement dans l'historique
            $this->statusHistory[] = [
                'previous' => $this->status?->value,
                'new' => $status->value,
                'timestamp' => $now->format('c')
            ];
            
            // Définir les timestamps spécifiques selon le statut
            if ($status === EventUserStatus::PARTICIPANT) {
                $this->participationConfirmedAt = $now;
            } elseif ($status === EventUserStatus::INVITED) {
                $this->invitedAt = $now;
            }
        }
        
        $this->status = $status;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getStatusChangedAt(): ?\DateTimeImmutable
    {
        return $this->statusChangedAt;
    }

    public function getParticipationConfirmedAt(): ?\DateTimeImmutable
    {
        return $this->participationConfirmedAt;
    }

    public function getInvitedAt(): ?\DateTimeImmutable
    {
        return $this->invitedAt;
    }

    public function getStatusHistory(): ?array
    {
        return $this->statusHistory;
    }

    /**
     * Vérifie si l'utilisateur est organisateur de l'événement
     */
    public function isOrganizer(): bool
    {
        return $this->status === EventUserStatus::ORGANIZER;
    }

    /**
     * Vérifie si l'utilisateur est participant à l'événement
     */
    public function isParticipant(): bool
    {
        return $this->status === EventUserStatus::PARTICIPANT;
    }

    /**
     * Vérifie si l'utilisateur est intéressé par l'événement
     */
    public function isInterested(): bool
    {
        return $this->status === EventUserStatus::INTERESTED;
    }

    /**
     * Vérifie si l'utilisateur a été invité à l'événement
     */
    public function isInvited(): bool
    {
        return $this->status === EventUserStatus::INVITED;
    }

    /**
     * Vérifie si l'utilisateur a une participation active (organisateur ou participant)
     */
    public function hasActiveParticipation(): bool
    {
        return $this->status->isActiveParticipation();
    }
}