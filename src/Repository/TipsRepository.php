<?php

namespace App\Repository;

use App\Entity\Tips;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tips|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tips|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tips[]    findAll()
 * @method Tips[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tips::class);
    }


    /**
     * @param string $username
     *
     * @return \App\Entity\Tips[]
     */
    public function getTipUserTips(string $username): array
    {
        $tips =  $this->findBy(['username' => $username]);
        $tips2match = [];

        foreach ($tips as $tip) {
            $tips2match[$tip->getMatchId()] = $tip;
        }

        return $tips2match;
    }

    // /**
    //  * @return Tips[] Returns an array of Tips objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tips
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
