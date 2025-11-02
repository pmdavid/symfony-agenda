<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

class CommonArea
{
    private int $id;

    private ?string $name;

    public function __construct(int $id, ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
