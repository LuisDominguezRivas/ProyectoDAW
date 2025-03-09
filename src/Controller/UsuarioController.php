<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsuarioController extends AbstractController
{
    #[Route('/usuario/{id}', name: 'usuario_ficha', requirements: ['id' => '\d+'])]
    public function ficha(int $id, EntityManagerInterface $entityManager): Response
    {
        // Consulta a la base de datos para obtener los datos del usuario
        $usuario = $entityManager->getRepository(Usuarios::class)->find($id);

        if (!$usuario) {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        return $this->render('usuario/ficha.html.twig', [
            'usuario' => $usuario,
        ]);
    }
}
