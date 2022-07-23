<?php

namespace App\Repository;

use App\Entity\Terceros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Terceros>
 *
 * @method Terceros|null find($id, $lockMode = null, $lockVersion = null)
 * @method Terceros|null findOneBy(array $criteria, array $orderBy = null)
 * @method Terceros[]    findAll()
 * @method Terceros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TercerosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Terceros::class);
    }

    public function add(Terceros $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Terceros $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listaTercerosByUsuario($usrId){
        return $this->getEntityManager()->createQuery('SELECT p.id,p.num_cuenta FROM App:Terceros p WHERE p.usr_registro = :id_usr')->setParameter('id_usr', $usrId);
    }
}
