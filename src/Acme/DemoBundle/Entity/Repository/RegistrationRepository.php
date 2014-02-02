<?php

namespace Acme\DemoBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class RegistrationRepository extends EntityRepository
{
    public function getAll()
    {
        return $this->createQueryBuilder('r')
            ->select('r, c, i')
            ->leftJoin('r.country', 'c')
            ->leftJoin('r.items', 'i')
            ->getQuery()
            ->getResult();
    }

    public function getFilterQb($filters = [], $locale = 'en')
    {
        return $this->createQueryBuilder('r')
            ->select('r, c, ct, i, it')
            ->leftJoin('r.country', 'c')
            ->leftJoin('c.translations', 'ct')
            ->leftJoin('r.items', 'i')
            ->leftJoin('i.translations', 'it')
            ->where("ct.locale = :locale AND it.locale = :locale")
            ->setParameter('locale', $locale);
    }
}
