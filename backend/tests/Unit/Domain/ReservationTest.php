<?php

namespace App\Tests\Domain;

use App\Domain\Enum\ReservationStatus;
use PHPUnit\Framework\TestCase;
use App\Domain\Reservation;
use App\Domain\CommonArea;

class ReservationTest extends TestCase
{
    public function testReservationInitialStatusIsReserved(): void
    {
        $reservation = new Reservation(new CommonArea(1), new \DateTimeImmutable(), 10);

        $this->assertEquals(ReservationStatus::RESERVED, $reservation->getStatus());
    }

    public function testReservationHasCorrectCommonArea(): void
    {
        $commonArea = new CommonArea(1, "Pista de Padel");
        $reservation = new Reservation($commonArea, new \DateTimeImmutable(), 10);

        $this->assertSame($commonArea, $reservation->getCommonArea());
    }

    public function testReservationHasCorrectDate(): void
    {
        $date = new \DateTimeImmutable('2025-11-02 10:00:00');
        $reservation = new Reservation(new CommonArea(1), $date, 10);

        $this->assertSame($date, $reservation->getDate());
    }

    public function testReservationHasCorrectHour(): void
    {
        $hour = 10;
        $reservation = new Reservation(new CommonArea(1), new \DateTimeImmutable(), $hour);

        $this->assertSame($hour, $reservation->getHour());
    }


}
