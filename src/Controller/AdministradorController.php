<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdministradorController extends AbstractController
{
    #[Route('/administrador', name: 'administrador')]
    public function index(): Response
    {
        return $this->render('administrador/index.html.twig', [
            'titulo' => 'Panel de Administrador',
        ]);
    }

    #[Route('/administrador/buscar', name: 'buscar_usuario', methods: ['POST'])]
    public function buscarUsuario(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $nombre = $request->request->get('nombre');

        // Consulta en la base de datos
        $query = $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\Usuario u
             WHERE u.nombre LIKE :nombre OR u.apellido LIKE :nombre'
        )->setParameter('nombre', '%' . $nombre . '%');

        $usuarios = $query->getResult();

        // Transformar los resultados en un formato JSON
        $resultados = [];
        foreach ($usuarios as $usuario) {
            $resultados[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellido' => $usuario->getApellido(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol(),
                'estado' => $usuario->getEstado(),
            ];
        }

        return new JsonResponse($resultados);
    }
}
