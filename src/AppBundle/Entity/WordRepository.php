<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * WordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WordRepository extends EntityRepository
{
    public function findWords ($match = ''){
        $q = $this->createQueryBuilder('w')
            ->where('w.word LIKE :match')
            ->orWhere('w.explanation LIKE :match')
            ->setParameter('match', '%'.$match.'%')
            ->orderBy('w.word', 'ASC')
            ->getQuery()
            ->getResult();

        return $q;
    }
}
