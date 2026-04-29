<?php
namespace App\Domain;

class ReservationService
{
    public function __construct(private ReservationRepositoryInterface $repository)
    {

    }

    /**
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @return array
     */
    public function getAvailability(CommonArea $commonArea, \DateTimeImmutable $date): array
    {
        $reservations = $this->repository->findByCommonAreaAndDate($commonArea, $date);

        $reservedHours = [];
        foreach ($reservations as $reservation) {
            $reservedHours[] = $reservation->getHour();
        }

        $slots = [];
        for ($hour = 9; $hour <= 21; $hour++) {
            $slots[] = [
                'hour' => $hour,
                'status' => in_array($hour, $reservedHours, true) ? 'RESERVADO' : 'LIBRE'
            ];
        }

        return $slots;
    }

    /**
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @param int $hour
     * @return bool
     */
    public function book(CommonArea $commonArea, \DateTimeImmutable $date, int $hour): bool
    {
        $existing = $this->repository->findByCommonAreaAndDate($commonArea, $date, $hour);

        if ($existing) {
            return false;
        }

        $reservation = new Reservation($commonArea, $date, $hour);
        $this->repository->save($reservation);

        return true;
    }

}
