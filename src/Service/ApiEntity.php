<?php

namespace App\Service;

use App\Entity\EntityAbstract;
use App\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

/**
 * Class ApiEntity
 * @package App\Service
 */
class ApiEntity
{
    /** @var EntityManager $entityManager */
    protected $entityManager;

    /** @var ApiCache $apiCache */
    protected $apiCache;

    /**
     * ApiEntity constructor.
     * @param EntityManager $entityManager
     * @param ApiCache $apiCache
     */
    public function __construct(EntityManager $entityManager, ApiCache $apiCache)
    {
        $this->entityManager = $entityManager;
        $this->apiCache = $apiCache;
    }

    /**
     * @param EntityAbstract $entity
     * @throws ORMException
     */
    public function create(EntityAbstract $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param EntityAbstract $entity
     * @throws ORMException
     */
    public function update(EntityAbstract $entity)
    {
        $this->entityManager->flush();
        $this->entityManager->refresh($entity);
    }

    /**
     * @param string $className
     * @param $id
     * @return EntityAbstract
     * @throws EntityNotFoundException
     */
    public function find(string $className, $id): EntityAbstract
    {
        $entity = $this->getRepository($className)->find($id);
        if (!$entity instanceof EntityAbstract) {
            throw new EntityNotFoundException('No ' . $className . ' Entity Found!');
        }
        return $entity;
    }

    /**
     * @param string $className
     * @param array $params
     * @return EntityAbstract
     * @throws EntityNotFoundException
     */
    public function findOneBy(string $className, array $params): EntityAbstract
    {
        $entity = $this->getRepository($className)->findOneBy($params);
        if (!$entity instanceof EntityAbstract) {
            throw new EntityNotFoundException('No ' . $className . ' Entity Found!');
        }
        return $entity;
    }

    /**
     * @param string $className
     * @return EntityRepository
     */
    public function getRepository(string $className): EntityRepository
    {
        return $this->entityManager->getRepository($className);
    }
}
