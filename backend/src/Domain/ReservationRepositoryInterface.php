<?php
namespace App\Domain;

interface ReservationRepositoryInterface
{
    /**
     * Devuelve todas las reservas de un CommonArea en un día determinado
     *
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @return Reservation[]
     */
    public function findByCommonAreaAndDate(CommonArea $commonArea, \DateTimeImmutable $date, ?int $hour = null): array;

    /**
     * Guarda una reserva
     *
     * @param Reservation $reservation
     * @return void
     */
    public function save(Reservation $reservation): void;
}
