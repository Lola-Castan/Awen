<?php

namespace App\Repository;

use App\Entity\Product;
use App\Enum\ProductStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Trouve tous les produits publiés
     * @return Product[] Returns an array of published Product objects
     */
    public function findPublishedProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', ProductStatus::Published)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Trouve les produits mis en avant (showcaseProduct = true)
     * @return Product[] Returns an array of featured Product objects
     */
    public function findFeaturedProducts(int $limit = 8): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.showcaseProduct = :showcase')
            ->setParameter('status', ProductStatus::Published)
            ->setParameter('showcase', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
