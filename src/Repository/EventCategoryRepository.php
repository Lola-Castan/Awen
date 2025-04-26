<?php

namespace App\Repository;

use App\Entity\EventCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventCategory>
 *
 * @method EventCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventCategory[]    findAll()
 * @method EventCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCategory::class);
    }

    /**
     * Retourne les catégories d'événements triées par nom
     * 
     * @return EventCategory[]
     */
    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('ec')
            ->orderBy('ec.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les catégories d'événements associées à un événement spécifique
     * 
     * @param int $eventId
     * @return EventCategory[]
     */
    public function findByEventId(int $eventId): array
    {
        return $this->createQueryBuilder('ec')
            ->innerJoin('ec.events', 'e')
            ->where('e.id = :eventId')
            ->setParameter('eventId', $eventId)
            ->orderBy('ec.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}