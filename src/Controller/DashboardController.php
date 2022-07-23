<?php

namespace App\Controller;

use App\Entity\Cuentas;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $idUsr = $this->getUser() ?? "";
        $nameUsr = self::nombreUsuario($idUsr);
        $infoUser = self::infoAccount($idUsr);
        return $this->render('dashboard/index.html.twig', [
            'nameUser' =>  $nameUsr,
            'titleh3' => 'Dashboard',
            'account' => $infoUser[0],
            'saldo' => $infoUser[1],
        ]);
    }

    private function nombreUsuario(string $id){
        if ($id){
            $dataUser = $this->em->getRepository(User::class)->findOneBy(['id' => $id]);
            return $dataUser->getNombres();
        }else{
            return "";
        }
    }

    private function infoAccount(string $id){
        if($id){
            $dataUser = $this->em->getRepository(Cuentas::class)->findOneBy(['user' => $id]);
            return array ($dataUser->getNumCuenta(), $dataUser->getSaldo());
        }else{
            return ['',''];
        }
    }
}
