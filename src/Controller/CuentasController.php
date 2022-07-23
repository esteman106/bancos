<?php

namespace App\Controller;

use App\Entity\Cuentas;
use App\Entity\Terceros;
use App\Entity\User;
use App\Form\AgregarCuentaType;
use App\Form\AgregarTerceroType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CuentasController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/cuentas', name: 'app_cuentas')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $idUsr = $this->getUser();
        $query = $this->em->getRepository(Cuentas::class)->findBy(['user'=>$idUsr]);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('cuentas/index.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'titleh3' => 'Cuentas Propias',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/terceros', name: 'cuentas_terceros')]
    public function terceros(PaginatorInterface $paginator, Request $request): Response
    {
        $idUsr = $this->getUser();
        $query = $this->em->getRepository(Terceros::class)->listaTercerosByUsuario($idUsr);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('cuentas/terceros.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'titleh3' => 'Cuentas de Terceros',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/cuentas/agregar', name: 'add_cuenta')]
    public function agregar(Request $request): Response
    {
        $idUsr = $this->getUser();
        $cuenta = new Cuentas();
        $add_form = $this->createForm(AgregarCuentaType::class,$cuenta);
        $add_form->handleRequest($request);
        if($add_form->isSubmitted() &&  $add_form->isValid()){
            $nickname = $add_form->getData()->getNickname();
            $numCuenta = $add_form->getData()->getNumCuenta();
            $objUser = $this->em->getRepository(User::class)->find($idUsr);
            $cuenta = new Cuentas($objUser,$numCuenta,$nickname, 0);
             $this->em->persist($cuenta);
             $this->em->flush();
            $this->addFlash('success','Registro satisfactorio, cuenta agregada');
           return $this->redirectToRoute('app_cuentas');
        }
        return $this->render('cuentas/agregar.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'agregarc_form' => $add_form->createView(),
        ]);
    }

    #[Route('/cuentas/agregart', name: 'add_cuentater')]
    public function agregarTercero(Request $request): Response
    {
        $idUsr = $this->getUser();
        $tercero = new Terceros();
        $formTercero = $this->createForm(AgregarTerceroType::class,$tercero);
        $formTercero->handleRequest($request);
        if($formTercero->isSubmitted() &&  $formTercero->isValid()){
            $fecha = new \DateTimeImmutable();
            $number = $formTercero->getData()->getNumCuenta();
            $objUser = $this->em->getRepository(Cuentas::class)->findOneBy(['num_cuenta'=>$number]);
            if($objUser->getUser()->getId()  === $idUsr->getId()){
               $this->addFlash('danger','Cuenta del mismo usuario, no se puede registrar!');
            }else{
                $tercero->setIdCuenta($objUser);
                $tercero->setUsrRegistro($idUsr);
                $tercero->setNumCuenta($number);
                $tercero->setCreatedAt($fecha);
                $tercero->setUpdateAt($fecha);
                $this->em->persist($tercero);
                $this->em->flush();
                $this->addFlash('success','Registro satisfactorio, cuenta agregada');
            }
            return $this->redirectToRoute('cuentas_terceros');
        }
        return $this->render('cuentas/agregarTercero.html.twig', [
            'nameUser' =>  self::nombreUsuario($idUsr),
            'agregart_form' => $formTercero->createView(),
        ]);
    }

    #[Route('/cuentas/desactivar/{id}', name: 'disable_account')]
    public function desactivar($id): Response
    {
        $this->addFlash('danger','Funcion no disponible');
        return $this->redirectToRoute('cuentas_terceros');
    }

    private function nombreUsuario(string $id){
        if ($id){
            $dataUser = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
            return $dataUser->getNombres();
        }else{
            return "";
        }
    }

    #[Route('/cuentas/desactivarter/{id}', name: 'disable_account_tercero')]
    public function desactivarTercero($id): Response
    {
        $this->addFlash('danger','Funcion no disponible');
        return $this->redirectToRoute('cuentas_terceros');
    }

}
