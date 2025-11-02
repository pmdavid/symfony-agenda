<?php

namespace App\Domain\Enum;

enum ReservationStatus: string
{
    case RESERVED = 'RESERVADO';
    case FREE     = 'LIBRE';


    public function isReserved(): bool
    {
        return $this === self::RESERVED;
    }

    public function isFree(): bool
    {
        return $this === self::FREE;
    }
}
