<?php

namespace App\Controller;

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
    public function index(): Response
    {
        return $this->render('inicio/index.html.twig', [
            'titulo' => 'Gimnasio Domrivas',
            'direccion' => 'Calle Laurel 12',
            'servicios' => [],
            'controller_name' => 'InicioController',
        ]);
    }
}
