<?php
namespace App\Application\UseCase;

use App\Domain\CommonArea;
use App\Domain\ReservationService;

class GetAvailabilityUseCase
{
    public function __construct(private ReservationService $reservationService)
    {

    }

    /**
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @return array
     */
    public function execute(CommonArea $commonArea, \DateTimeImmutable $date): array
    {
        return $this->reservationService->getAvailability($commonArea, $date);
    }
}
