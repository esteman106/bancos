<?php

namespace App\Controller;

use App\Entity\Cuentas;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    const saldoInit = 2000;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/registration', name: 'userRegistration')]
    public function userRegistration(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $registro_form = $this->createForm(UserType::class,$user);
        $registro_form->handleRequest($request);
        if($registro_form->isSubmitted() && $registro_form->isValid()){
            $fecha = new \DateTimeImmutable();
            $passtemp = $registro_form->get('password')->getData();
            $encripta = $passwordHasher->hashPassword($user,$passtemp);
            $user->setPassword($encripta);
            $user->setRoles(['ROLE_USER']);
            $user->setCreatedAt($fecha);
            $user->setUpdatedAt($fecha);
            $this->em->persist($user);
            $this->em->flush();
            $id = $user->getId();
            self::registerAccount($id);
            $this->addFlash('success','Registro satisfactorio, puedes iniciar sesion');
            return $this->redirectToRoute('userRegistration');
        }
        return $this->render('user/index.html.twig', [
            'registro_form' => $registro_form->createView(),
        ]);
    }

    private function registerAccount($id){
        $objUser = $this->em->getRepository(User::class)->find($id);
        $number = 348 . mt_rand(0, 999);
        $cuenta = new Cuentas($objUser,$number,$number,self::saldoInit);
        $this->em->persist($cuenta);
        $this->em->flush();
    }
}
