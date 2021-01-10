<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ConsumptionRepository
 */
class ConsumptionRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findConsumptionsForToday(User $user)
    {
        $dt = new DateTime();

        $qb = $this->createQueryBuilder('consumption');
        $qb->andWhere('DATE(consumption.dateTime) = :date');
        $qb->setParameter('date', $dt->format('Y-m-d'));
        $qb->andWhere('consumption.user = :user');
        $qb->setParameter('user', $user->getId());


        return $qb->getQuery()->getResult();

    }
}