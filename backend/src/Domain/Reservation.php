<?php

namespace App\Domain;

use App\Domain\Enum\ReservationStatus;

final class Reservation
{
    private CommonArea $commonArea;
    private \DateTimeImmutable $date;
    private int $hour;
    private ReservationStatus $status;

    public function __construct(CommonArea $commonArea, \DateTimeImmutable $date, int $hour)
    {
        $this->commonArea = $commonArea;
        $this->date       = $date;
        $this->hour       = $hour;
        $this->status     = ReservationStatus::RESERVED;
    }

    public function getCommonArea(): CommonArea
    {
        return $this->commonArea;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getStatus(): ReservationStatus
    {
        return $this->status;
    }

    public function markReserved(): void
    {
        $this->status = ReservationStatus::RESERVED;
    }

    public function markFree(): void
    {
        $this->status = ReservationStatus::FREE;
    }
}
