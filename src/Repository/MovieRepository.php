<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MovieRepository
 * @package App\Repository
 */
class MovieRepository extends ServiceEntityRepository
{
    private const USERS_KEYS = 'm.users';

    /**
     * MovieRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param User $user
     * @return Movie[]
     */
    public function findAllMovieByUser(User $user)
    {
        return $this->createQueryBuilder('m')
            ->join(self::USERS_KEYS, 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Movie[]
     */
    public function findAllMovieWithUser()
    {
        return $this->createQueryBuilder('m')
            ->join(self::USERS_KEYS, 'u')
            ->addSelect('COUNT(u) as HIDDEN totalUsers')
            ->having('totalUsers > 0')
            ->groupBy('m')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Movie|null
     */
    public function findBestMovie(): ?Movie
    {
        try {
            $movie = $this->createQueryBuilder('m')
                ->join(self::USERS_KEYS, 'u')
                ->addSelect('MAX(u) as HIDDEN maxUsers')
                ->groupBy('m')
                ->orderBy('maxUsers', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $movie = null;
        }

        return $movie;
    }
}
