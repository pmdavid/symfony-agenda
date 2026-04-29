<?php
namespace App\Application\UseCase;

use App\Domain\ReservationService;
use App\Domain\CommonArea;

class ReservationUseCase
{
    public function __construct(private ReservationService $reservationService) {}

    public function execute(CommonArea $commonArea, \DateTimeImmutable $date, int $hour): bool
    {

        return $this->reservationService->book($commonArea, $date, $hour);
    }
}
