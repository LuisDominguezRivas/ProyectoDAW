<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\RegistroForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'registro')]
    public function index(Request $request, ManagerRegistry $doctrine ,UserPasswordHasherInterface $passwordHasher): Response
    {
        $usuarios= new Usuarios();
        $form= $this->createForm(RegistroForm::class,$usuarios);//creacion del formulario
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if($form->isSubmitted()&&$form->isValid()){
           $plaintextPassword = $form['contrasena']->getData();
           $hashedPassword = $passwordHasher->hashPassword($usuarios,$plaintextPassword);

           $usuarios->setContrasena($hashedPassword);
           //persistimos(guardamos) el usuario que se esta creando en el formulario
           $entityManager->persist($usuarios);
           //forzamos la persistencia de los datos en BD
           $entityManager->flush();
           $this->addFlash('exito','Usuario registrado');
           return $this->redirectToRoute('registro');
        }


        $user = $this->getUser();
        return $this->render('registro/index.html.twig', [
            'user' => $user,
            'opcion' => 'registro',
            'formulario' => $form->createView()
        ]);
    }
}
