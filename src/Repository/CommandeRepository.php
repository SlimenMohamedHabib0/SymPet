<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }
    public function findByUserQuery($user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();
    }
    public function getCATotal(): float {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.total)')
            ->where('c.statut != :annulee')
            ->setParameter('annulee', 'annulee')
            ->getQuery()->getSingleScalarResult() ?? 0;
    }


    public function getCommandesParJour(int $days = 30): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT
            DATE(created_at) AS jour,
            COUNT(id) AS nb
        FROM commande
        WHERE created_at >= :date
        GROUP BY DATE(created_at)
        ORDER BY jour ASC
    ";

        return $conn->executeQuery(
            $sql,
            [
                'date' => (new \DateTimeImmutable('-'.$days.' days'))
                    ->format('Y-m-d H:i:s')
            ]
        )->fetchAllAssociative();
    }public function getCAParMois(): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "
        SELECT
            MONTH(created_at) AS mois,
            SUM(total) AS total
        FROM commande
        GROUP BY MONTH(created_at)
        ORDER BY mois ASC
    ";

    return $conn->executeQuery($sql)
        ->fetchAllAssociative();
}
    public function getTopProduit(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.nom, SUM(lc.quantite) as total
        FROM ligne_commande lc
        JOIN produit p ON p.id = lc.produit_id
        GROUP BY p.id
        ORDER BY total DESC
        LIMIT 1
    ";

        return $conn->executeQuery($sql)
            ->fetchAssociative();
    }

    public function getTopCategorie(): ?array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT c.nom, COUNT(lc.id) as total
        FROM ligne_commande lc
        JOIN produit p ON p.id = lc.produit_id
        JOIN categorie c ON c.id = p.categorie_id
        GROUP BY c.id
        ORDER BY total DESC
        LIMIT 1
    ";

        return $conn->executeQuery($sql)
            ->fetchAssociative();
    }

    public function getCommandesParMois(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT
            MONTH(created_at) AS mois,
            COUNT(id) AS total
        FROM commande
        GROUP BY MONTH(created_at)
        ORDER BY mois ASC
    ";

        return $conn->executeQuery($sql)
            ->fetchAllAssociative();
    }



    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
