<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function search(string $q = '', ?int $categorieId = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.categorie', 'c')
            ->addSelect('c');

        if ($q) {
            $qb->andWhere('p.nom LIKE :q OR p.description LIKE :q')
                ->setParameter('q', '%'.$q.'%');
        }

        if ($categorieId) {
            $qb->andWhere('p.categorie = :cat')
                ->setParameter('cat', $categorieId);
        }

        return $qb->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchQuery(
        string $q = '',
        ?int $categorieId = null,
        ?float $prixMin = null,
        ?float $prixMax = null,
        string $tri = 'recent'
    ): Query {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.categorie', 'c')
            ->addSelect('c');

        if ($q) {
            $qb->andWhere('p.nom LIKE :q OR p.description LIKE :q')
                ->setParameter('q', '%'.$q.'%');
        }

        if ($categorieId) {
            $qb->andWhere('p.categorie = :cat')
                ->setParameter('cat', $categorieId);
        }

        if ($prixMin !== null) {
            $qb->andWhere('p.prix >= :pmin')
                ->setParameter('pmin', $prixMin);
        }

        if ($prixMax !== null) {
            $qb->andWhere('p.prix <= :pmax')
                ->setParameter('pmax', $prixMax);
        }

        match($tri) {
            'prix_asc'  => $qb->orderBy('p.prix', 'ASC'),
            'prix_desc' => $qb->orderBy('p.prix', 'DESC'),
            'nom'       => $qb->orderBy('p.nom', 'ASC'),
            default     => $qb->orderBy('p.createdAt', 'DESC'),
        };

        return $qb->getQuery();
    }
    public function countProduitsRupture(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.stock <= 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getProduitsParCategorie(): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.nom as categorie, COUNT(p.id) as total')
            ->join('p.categorie', 'c')
            ->groupBy('c.id')
            ->getQuery()
            ->getArrayResult();
    }
}
