<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * Trouve les images d'un produit triées par position
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.product = :product')
            ->setParameter('product', $product)
            ->orderBy('i.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve l'image principale d'un produit (position = 0 ou la première)
     */
    public function findMainImageByProduct(Product $product): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.product = :product')
            ->setParameter('product', $product)
            ->orderBy('i.position', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}