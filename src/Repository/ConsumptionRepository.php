<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;

/**
 * Class ConsumptionRepository
 */
class ConsumptionRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function findConsumptionsForToday(User $user): array
    {
        $dt = new DateTime();

        $qb = $this->createQueryBuilder('consumption');
        $qb->andWhere('DATE(consumption.dateTime) = :date');
        $qb->setParameter('date', $dt->format('Y-m-d'));
        $qb->andWhere('consumption.user = :user');
        $qb->setParameter('user', $user->getId());
        $qb->orderBy('consumption.dateTime', 'ASC');

        return $qb->getQuery()->getResult();
    }
}