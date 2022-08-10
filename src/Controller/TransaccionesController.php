<?php

namespace App\Controller;

use App\Entity\Cuentas;
use App\Entity\Terceros;
use App\Entity\Transacciones;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransaccionesController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/transacciones', name: 'app_transacciones')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $idUsr = $this->getUser();
        $objUser = $this->em->getRepository(Transacciones::class)->listadoTransacciones($idUsr);
        $pagination = $paginator->paginate(
            $objUser,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('transacciones/index.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'titleh3' => 'Transacciones',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/transacciones/cuentaspropias', name: 'transaccion_propias')]
    public function entreCuentas(): Response
    {
        $idUsr = $this->getUser();
        $objUser = $this->em->getRepository(Cuentas::class)->findBy(['user'=>$idUsr]);
        return $this->render('transacciones/entreCuentas.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'titleh3' => 'Transaccion entre cuentas propias',
            'listCuentas' => $objUser,
        ]);
    }

    #[Route('/transacciones/terceros', name: 'transaccion_terceros')]
    public function entreTerceros(): Response
    {
        $idUsr = $this->getUser();
        $objUser = $this->em->getRepository(Cuentas::class)->findBy(['user'=>$idUsr]);
        $objTerceros = $this->em->getRepository(Terceros::class)->findBy(['usr_registro'=>$idUsr]);
        return $this->render('transacciones/otrasCuentas.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'titleh3' => 'Transaccion entre cuentas de terceros inscritas',
            'listCuentas' => $objUser,
            'listTerceros' => $objTerceros,
        ]);
    }

    #[Route('/transacciones/store', name: 'store_transaccion')]
    public function store(Request $request): Response
    {
        $idUsr = $this->getUser();
        $token = $request->request->get("token");
        if (!$this->isCsrfTokenValid('transaccion', $token))
        {
            return new Response('Operacion no permitida', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }
        $origen = $request->request->get("origen");
        $destino = $request->request->get("destino");
        $monto =  $request->request->get("monto");
        $tipo =  $request->request->get("evento");
        $saldoOrigen = self::saldoCuenta($origen);
        $saldodestino = self::saldoCuentaDestino($destino);
        if($origen === $destino){
            $this->addFlash('danger','Proceso no posible entre mismas cuentas!');
        }elseif ($monto > $saldoOrigen){
            $this->addFlash('warning','No hay saldo suficiente');
        }else{
            $transaccion = new Transacciones();
            self::actualizarSaldo($origen,$destino,$saldoOrigen,$saldodestino,$monto);
            $transaccion->setCreatedAt(new \DateTimeImmutable());
            $destino = $this->em->getRepository(Cuentas::class)->find($destino);
            $origen = $this->em->getRepository(Cuentas::class)->find($origen);
            $transaccion->setCuentaDestino($destino);
            $transaccion->setCuentaOrigen($origen);
            $transaccion->setTipo($tipo);
            $transaccion->setMonto($monto);
            $transaccion->setUser($idUsr);
            $this->em->persist($transaccion);
            $this->em->flush();
            $this->addFlash('success','Transferencia procesada!');
        }
        return $this->redirectToRoute('app_transacciones');
    }

    private function saldoCuenta($idCuenta){
        $query = $this->em->getRepository(Cuentas::class)->findOneBy(['id'=>$idCuenta]);
        return $query->getSaldo();
    }

    private function saldoCuentaDestino($idCuenta){
        $query = $this->em->getRepository(Cuentas::class)->findOneBy(['id'=>$idCuenta]);
        return $query->getSaldo();
    }

    private function actualizarSaldo($origen,$destino,$saldoOrigen,$saldodestino,$monto){
        $saldoFinalOrigen = $saldoOrigen - $monto;
        $saldoFinalDestino = $saldodestino + $monto;
        $this->em->getRepository(Cuentas::class)->retirarSaldo($origen,$saldoFinalOrigen);
        $this->em->getRepository(Cuentas::class)->ingresarSaldo($destino,$saldoFinalDestino);
    }

    private function nombreUsuario(string $id){
        if ($id){
            $dataUser = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
            return $dataUser->getNombres();
        }else{
            return "";
        }
    }
}
