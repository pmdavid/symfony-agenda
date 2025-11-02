<?php

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "reservation")]
class ReservationEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private int $commonAreaId;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: "integer")]
    private int $hour;

    #[ORM\Column(type: "string", length: 10)]
    private string $status;

    // ----------------------
    // Getters & Setters
    // ----------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommonAreaId(): int
    {
        return $this->commonAreaId;
    }

    public function setCommonAreaId(int $commonAreaId): self
    {
        $this->commonAreaId = $commonAreaId;
        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function setHour(int $hour): self
    {
        $this->hour = $hour;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
