<?php

namespace App\Repository;

use App\Entity\EventUser;
use App\Entity\Event;
use App\Entity\User;
use App\Enum\EventUserStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventUser>
 *
 * @method EventUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventUser[]    findAll()
 * @method EventUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventUser::class);
    }

    /**
     * Trouve la relation entre un événement et un utilisateur spécifiques
     */
    public function findRelation(Event $event, User $user): ?EventUser
    {
        return $this->findOneBy([
            'event' => $event,
            'user' => $user,
        ]);
    }

    /**
     * Renvoie tous les utilisateurs qui ont un statut spécifique pour un événement donné
     */
    public function findUsersByEventAndStatus(Event $event, EventUserStatus $status): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\User u
            JOIN App\Entity\EventUser eu WITH eu.user = u
            WHERE eu.event = :event
            AND eu.status = :status'
        )
        ->setParameter('event', $event)
        ->setParameter('status', $status);

        return $query->getResult();
    }

    /**
     * Renvoie tous les utilisateurs qui sont organisateurs d'un événement donné
     */
    public function findOrganizersByEvent(Event $event): array
    {
        return $this->findUsersByEventAndStatus($event, EventUserStatus::ORGANIZER);
    }

    /**
     * Renvoie tous les utilisateurs qui participent à un événement donné
     */
    public function findParticipantsByEvent(Event $event): array
    {
        return $this->findUsersByEventAndStatus($event, EventUserStatus::PARTICIPANT);
    }

    /**
     * Renvoie tous les utilisateurs qui sont intéressés par un événement donné
     */
    public function findInterestedUsersByEvent(Event $event): array
    {
        return $this->findUsersByEventAndStatus($event, EventUserStatus::INTERESTED);
    }

    /**
     * Renvoie tous les utilisateurs invités à un événement donné
     */
    public function findInvitedUsersByEvent(Event $event): array
    {
        return $this->findUsersByEventAndStatus($event, EventUserStatus::INVITED);
    }

    /**
     * Renvoie tous les événements ayant une relation spécifique avec un utilisateur
     */
    public function findEventsByUserAndStatus(User $user, EventUserStatus $status): array
    {
        return $this->createQueryBuilder('eu')
            ->select('e')
            ->join('eu.event', 'e')
            ->where('eu.user = :user')
            ->andWhere('eu.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }

    /**
     * Renvoie tous les événements que l'utilisateur organise
     */
    public function findEventsOrganizedByUser(User $user): array
    {
        return $this->findEventsByUserAndStatus($user, EventUserStatus::ORGANIZER);
    }
    
    /**
     * Renvoie tous les événements auxquels l'utilisateur participe
     */
    public function findEventsUserParticipatesIn(User $user): array
    {
        return $this->findEventsByUserAndStatus($user, EventUserStatus::PARTICIPANT);
    }
    
    /**
     * Renvoie tous les événements qui intéressent l'utilisateur
     */
    public function findEventsUserIsInterestedIn(User $user): array
    {
        return $this->findEventsByUserAndStatus($user, EventUserStatus::INTERESTED);
    }

    /**
     * Renvoie tous les événements auxquels l'utilisateur a été invité
     */
    public function findEventsUserIsInvitedTo(User $user): array
    {
        return $this->findEventsByUserAndStatus($user, EventUserStatus::INVITED);
    }

    /**
     * Compte le nombre d'utilisateurs ayant un statut spécifique pour un événement
     */
    public function countUsersByEventAndStatus(Event $event, EventUserStatus $status): int
    {
        return $this->createQueryBuilder('eu')
            ->select('COUNT(eu.id)')
            ->where('eu.event = :event')
            ->andWhere('eu.status = :status')
            ->setParameter('event', $event)
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre de participants à un événement
     */
    public function countParticipants(Event $event): int
    {
        return $this->countUsersByEventAndStatus($event, EventUserStatus::PARTICIPANT);
    }

    /**
     * Récupère l'historique des changements de statut pour une relation utilisateur-événement
     */
    public function getStatusHistory(Event $event, User $user): ?array
    {
        $relation = $this->findRelation($event, $user);
        return $relation ? $relation->getStatusHistory() : null;
    }

    /**
     * Trouve toutes les relations d'événement avec un utilisateur, groupées par statut
     */
    public function findEventRelationsByUser(User $user): array
    {
        $result = [];
        foreach (EventUserStatus::cases() as $status) {
            $result[$status->value] = $this->findEventsByUserAndStatus($user, $status);
        }
        return $result;
    }

    /**
     * Trouve les événements actifs (à venir) pour un utilisateur
     */
    public function findUpcomingEventsByUser(User $user): array
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('eu')
            ->select('e')
            ->join('eu.event', 'e')
            ->where('eu.user = :user')
            ->andWhere('e.startDateTime > :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('e.startDateTime', 'ASC')
            ->getQuery()
            ->getResult();
    }
}