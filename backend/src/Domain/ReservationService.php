<?php
namespace App\Domain;

class ReservationService
{
    // Symfony inyecta automáticamente la implementación de ReservationRepositoryInterface, permitiendo usarlo como $this->repository
    public function __construct(private ReservationRepositoryInterface $repository)
    {

    }

    /**
     * Devuelve la disponibilidad de un CommonArea en un día
     *
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
     * Reserva un slot si está libre
     *
     * @param CommonArea $commonArea
     * @param \DateTimeImmutable $date
     * @param int $hour
     * @return bool
     */
    public function book(CommonArea $commonArea, \DateTimeImmutable $date, int $hour): bool
    {
        // Consultamos si existe reserva para esa hora concreta
        $existing = $this->repository->findByCommonAreaAndDate($commonArea, $date, $hour);

        // Aunque front controla y evita que no se pueda reservar un slot ya ocupado, siempre hay que validar en backend ya que se pueden hacer llamadas directas
        if ($existing) {
            return false;
        }

        // Creamos y guardamos la nueva reserva
        $reservation = new Reservation($commonArea, $date, $hour);
        $this->repository->save($reservation);

        return true;
    }

}
