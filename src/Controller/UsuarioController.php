<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UsuarioController extends AbstractController
{
    #[Route('/usuario/{id}', name: 'usuario_ficha', requirements: ['id' => '\d+'])]
    public function ficha(int $id, EntityManagerInterface $entityManager): Response
    {
        // Consulta a la base de datos para obtener los datos del usuario
        $usuario = $entityManager->getRepository('App\Entity\Usuario')->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('usuario/ficha.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}
