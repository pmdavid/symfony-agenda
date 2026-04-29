<?php
namespace App\Domain;

interface ReservationRepositoryInterface
{
    /**
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @return Reservation[]
     */
    public function findByCommonAreaAndDate(CommonArea $commonArea, \DateTimeImmutable $date, ?int $hour = null): array;

    /**
     * @param Reservation $reservation
     * @return void
     */
    public function save(Reservation $reservation): void;
}
