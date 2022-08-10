<?php

namespace App\Repository;

use App\Entity\Transacciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transacciones>
 *
 * @method Transacciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transacciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transacciones[]    findAll()
 * @method Transacciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransaccionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacciones::class);
    }

    public function add(Transacciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transacciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listaTransaccionesByUsuario($usrId){
        return $this->getEntityManager()->createQuery('SELECT p.id,p.num_cuenta FROM App:Transacciones p WHERE p.user_id = :id_usr')->setParameter('id_usr', $usrId);
    }

    public function listadoTransacciones($id){
        return $this->getEntityManager()->createQuery('SELECT t.monto,t.created_at, c.num_cuenta AS origen, d.num_cuenta AS destino
            FROM App:Transacciones t
            INNER JOIN t.cuenta_origen c INNER JOIN t.cuenta_destino d
            WHERE t.user = :user')->setParameter('user', $id);
    }

}
