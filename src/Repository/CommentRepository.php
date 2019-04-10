<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findAllCommentTrick($page, $limit, Trick $trick)
    {
        $qb = $this->_em->createQueryBuilder('c');
        $qb->select('c')
            ->from('App\Entity\Comment', 'c')
            ->leftJoin('c.trick', 'a')
            ->where('a.id =:id')
            ->orderBy('c.dateCreation', 'DESC')
            ->setParameter('id', $trick->getId())
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }

    public function findAllCommentUser($page, $limit, $userId)
    {
        $qb = $this->_em->createQueryBuilder('c');
        $qb->select('c')
            ->from('App\Entity\Comment', 'c')
            ->leftJoin('c.user', 'a')
            ->where('a.id =:id')
            ->orderBy('c.dateCreation', 'DESC')
            ->setParameter('id', $userId)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
