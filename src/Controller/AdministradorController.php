<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        $apellido = $request->request->get('apellido');
        $email = $request->request->get('email');
        $telefono = $request->request->get('telefono');
        $fechaNacimiento = $request->request->get('fecha_nacimiento');
        $rol = $request->request->get('rol');
        $estado = $request->request->get('estado');

        // Validar que los campos obligatorios no estén vacíos
        if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($fechaNacimiento) || empty($rol) || empty($estado)) {
            return new JsonResponse(['error' => 'Todos los campos son obligatorios.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar nombre y apellidos (solo letras y espacios, longitud mínima y máxima)
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,50}$/', $nombre) || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,50}$/', $apellido)) {
            return new JsonResponse(['error' => 'El nombre y apellido deben contener solo letras y espacios (entre 3 y 50 caracteres).'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar formato de correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'El formato del correo electrónico no es válido.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar número de teléfono (solo números y exactamente 9 dígitos)
        if (!preg_match('/^\d{9}$/', $telefono)) {
            return new JsonResponse(['error' => 'El teléfono debe contener exactamente 9 dígitos numéricos.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar formato y rango de la fecha de nacimiento
        $fechaActual = new \DateTime();
        $fechaNacimientoObj = \DateTime::createFromFormat('Y-m-d', $fechaNacimiento);

        if (!$fechaNacimientoObj || $fechaNacimientoObj > $fechaActual) {
            return new JsonResponse(['error' => 'La fecha de nacimiento debe tener un formato válido (YYYY-MM-DD) y no puede ser futura.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar roles (solo admin o cliente)
        if (!in_array($rol, ['admin', 'cliente'])) {
            return new JsonResponse(['error' => 'El rol debe ser "admin" o "cliente".'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Validar estado (solo activo o inactivo)
        if (!in_array($estado, ['activo', 'inactivo'])) {
            return new JsonResponse(['error' => 'El estado debe ser "activo" o "inactivo".'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Consulta en la base de datos
        $query = $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\Usuario u
             WHERE (u.nombre LIKE :nombre OR u.apellido LIKE :apellido)
             AND u.email = :email
             AND u.telefono = :telefono'
        )
        ->setParameters([
            'nombre' => '%' . $nombre . '%',
            'apellido' => '%' . $apellido . '%',
            'email' => $email,
            'telefono' => $telefono
        ])
        ->setMaxResults(50); // Limitar resultados

        $usuarios = $query->getResult();

        if (empty($usuarios)) {
            return new JsonResponse(['error' => 'No se encontraron usuarios con los criterios especificados.'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Transformar los resultados en un formato JSON
        $resultados = [];
        foreach ($usuarios as $usuario) {
            $resultados[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellido' => $usuario->getApellido(),
                'email' => $usuario->getEmail(),
                'telefono' => $usuario->getTelefono(),
                'fecha_nacimiento' => $usuario->getFechaNacimiento()->format('Y-m-d'),
                'rol' => $usuario->getRol(),
                'estado' => $usuario->getEstado(),
            ];
        }

        return new JsonResponse($resultados);
    }
}

