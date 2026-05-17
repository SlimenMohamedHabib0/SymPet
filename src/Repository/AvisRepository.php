<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    public function getAvisParNote(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.note as note, COUNT(a.id) as total')
            ->groupBy('a.note')
            ->orderBy('a.note', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getMoyenneAvis(): float
    {
        return (float) ($this->createQueryBuilder('a')
            ->select('AVG(a.note)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0);
    }
}
