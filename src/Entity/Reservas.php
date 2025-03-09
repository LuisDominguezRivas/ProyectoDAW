<?php

namespace App\Entity;

use App\Repository\ReservasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservasRepository::class)]
class Reservas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuarios::class, inversedBy: "reservas")]
    #[ORM\JoinColumn(name: "id_usuario", referencedColumnName: "id", nullable: false)]
    private ?Usuarios $id_usuario = null;

    #[ORM\ManyToOne(targetEntity: Servicios::class, inversedBy: "reservas")]
    #[ORM\JoinColumn(name: "id_servicio", referencedColumnName: "id", nullable: false)]
    private $id_servicio;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $fecha_reserva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?Usuarios
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?Usuarios $id_usuario): static
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getIdServicio(): ?Servicios
    {
        return $this->id_servicio;
    }

    public function setIdServicio(?Servicios $id_servicio): static
    {
        $this->id_servicio = $id_servicio;

        return $this;
    }

    public function getFechaReserva(): ?\DateTimeInterface
    {
        return $this->fecha_reserva;
    }

    public function setFechaReserva(?\DateTimeInterface $fecha_reserva): static
    {
        $this->fecha_reserva = $fecha_reserva;

        return $this;
    }
}
