<?php

namespace App\Controller;

use App\Entity\Servicios;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InicioController extends AbstractController
{
    // Redirigir por defecto a la pagina inicio
    #[Route('/', name: 'inicio_frontal')]
    public function redirectToInicio(): Response
    {
        return $this->redirectToRoute('inicio');
    }

    #[Route('/inicio', name: 'inicio')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $servicios=$entityManager->getRepository(Servicios::class)->findAll();

        return $this->render('inicio/index.html.twig', [
            'servicios' => $servicios,
            'controller_name' => 'InicioController',
        ]);
    }
}
