<?php


namespace App\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\ReservationService;
use App\Domain\CommonArea;
use App\Domain\Reservation;
use App\Domain\ReservationRepositoryInterface;

class ReservationServiceTest extends TestCase
{
    public function testGetAvailabilityReturnsCorrectSlots(): void
    {
        $commonArea = new CommonArea(1, "Pista de Padel");
        $date = new \DateTimeImmutable('2025-11-02');

        $repository = $this->createMock(ReservationRepositoryInterface::class);
        $repository->method('findByCommonAreaAndDate')
            ->with($commonArea, $date)
            ->willReturn([
                new Reservation($commonArea, $date, 10), // Simulamos reserva creadaa a las 10
                new Reservation($commonArea, $date, 12), // Simulamos reserva creadaa a las 12
            ]);

        $service = new ReservationService($repository);

        $availability = $service->getAvailability($commonArea, $date);

        $this->assertSame('RESERVADO', $availability[1]['status']); // 10:00
        $this->assertSame('RESERVADO', $availability[3]['status']); // 12:00
        $this->assertSame('LIBRE', $availability[0]['status']); // 9:00 Es libre porque no hemos creado reserva para esa hora
        $this->assertCount(13, $availability);
    }

    public function testBookSucceedsIfSlotFree(): void
    {
        $commonArea = new CommonArea(1);
        $date = new \DateTimeImmutable('2025-11-02');
        $hour = 10;

        $repository = $this->createMock(ReservationRepositoryInterface::class);
        $repository->method('findByCommonAreaAndDate')
            ->with($commonArea, $date, $hour)
            ->willReturn([]);

        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Reservation::class));

        $service = new ReservationService($repository);

        $result = $service->book($commonArea, $date, $hour);

        $this->assertTrue($result);
    }

    public function testBookFailsIfSlotOccupied(): void
    {
        $commonArea = new CommonArea(1);
        $date = new \DateTimeImmutable('2025-11-02');
        $hour = 10;

        $repository = $this->createMock(ReservationRepositoryInterface::class);
        $repository->method('findByCommonAreaAndDate')
            ->with($commonArea, $date, $hour)
            ->willReturn([new Reservation($commonArea, $date, $hour)]);

        $repository->expects($this->never())
            ->method('save');

        $service = new ReservationService($repository);

        $result = $service->book($commonArea, $date, $hour);

        $this->assertFalse($result);
    }
}
