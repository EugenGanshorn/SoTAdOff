<?php

namespace App\Repository;

use App\Entity\DeviceGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceGroup[]    findAll()
 * @method DeviceGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviceGroup::class);
    }
}
