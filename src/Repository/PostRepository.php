<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Trouve tous les posts publiés triés par date de création décroissante
     *
     * @return Post[] Returns an array of Post objects
     */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :val')
            ->setParameter('val', true)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les posts d'un utilisateur spécifique
     *
     * @param User $user L'utilisateur dont on veut les posts
     * @return Post[] Returns an array of Post objects
     */
    public function findByAuthor(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.author = :user')
            ->setParameter('user', $user)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les posts publiés les plus récents
     *
     * @param int $limit Nombre maximum de posts à retourner
     * @return Post[] Returns an array of Post objects
     */
    public function findRecentPublished(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :val')
            ->setParameter('val', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}