<?php

namespace App\Repository;

use App\Entity\Event;
use App\Enum\EventStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Récupère les événements ayant un des statuts spécifiés, triés par date de début
     * 
     * @param EventStatus[] $statuses
     * @return Event[]
     */
    public function findByStatuses(array $statuses): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->orderBy('e.startDateTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les événements à venir ayant un statut publié
     * 
     * @return Event[]
     */
    public function findUpcomingPublished(): array
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->andWhere('e.startDateTime > :now')
            ->setParameter('status', EventStatus::Published)
            ->setParameter('now', $now)
            ->orderBy('e.startDateTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Event[] Returns an array of Event objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
