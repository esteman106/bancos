<?php

namespace App\Repository;

use App\Entity\Cuentas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cuentas>
 *
 * @method Cuentas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuentas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuentas[]    findAll()
 * @method Cuentas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cuentas::class);
    }

    public function add(Cuentas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cuentas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function retirarSaldo($idCuenta,$nuevoSaldo){
        return $this->getEntityManager()->createQuery('UPDATE App:Cuentas c SET c.saldo = :saldo WHERE c.id = :id')
            ->setParameter('saldo', $nuevoSaldo)->setParameter('id', $idCuenta)->execute();
    }

    public function ingresarSaldo($idCuenta,$nuevoSaldo){
        return $this->getEntityManager()->createQuery('UPDATE App:Cuentas c SET c.saldo = :saldo WHERE c.id = :id')
            ->setParameter('saldo', $nuevoSaldo)->setParameter('id', $idCuenta)->execute();
    }

}
