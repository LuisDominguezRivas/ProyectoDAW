<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Servicio;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReservaController extends AbstractController
{
    #[Route('/reservas', name: 'reservas')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Obtener todas las clases/servicios
        $servicios = $entityManager->getRepository(Servicio::class)->findAll();

        return $this->render('reservas/index.html.twig', [
            'servicios' => $servicios,
        ]);
    }

    #[Route('/reservar', name: 'reservar', methods: ['POST'])]
    public function reservar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $servicioId = $request->request->get('servicio_id');
        $usuarioId = $this->getUser() ? $this->getUser()->getId() : null; // Si hay autenticación
        $usuario = $entityManager->getRepository(Usuario::class)->find($usuarioId);
        $servicio = $entityManager->getRepository(Servicio::class)->find($servicioId);

        if (!$servicio || !$usuario) {
            $this->addFlash('error', 'Servicio o usuario no encontrado.');
            return $this->redirectToRoute('reservas');
        }

        // Verificar capacidad
        $reservas = $entityManager->getRepository(Reserva::class)->count(['idServicio' => $servicioId]);

        if ($servicio->getCapacidad() <= $reservas) {
            $this->addFlash('error', 'La capacidad máxima para este servicio ha sido alcanzada.');
            return $this->redirectToRoute('reservas');
        }

        // Crear la reserva
        $reserva = new Reserva();
        $reserva->setIdUsuario($usuario);
        $reserva->setIdServicio($servicio);
        $reserva->setFechaReserva(new \DateTime());

        $entityManager->persist($reserva);
        $entityManager->flush();

        $this->addFlash('success', 'Reserva realizada con éxito.');
        return $this->redirectToRoute('reservas');
    }
}
