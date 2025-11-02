<?php

namespace App\Infrastructure;

use App\Domain\CommonArea;
use App\Domain\Reservation;
use App\Domain\ReservationRepositoryInterface;
use App\Infrastructure\Entity\ReservationEntity;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Devuelve todas las reservas de un CommonArea en un día determinado
     */
    public function findByCommonAreaAndDate(CommonArea $commonArea, \DateTimeImmutable $date, ?int $hour = null): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('r')
            ->from(ReservationEntity::class, 'r')
            ->where('r.commonAreaId = :areaId')
            ->andWhere('r.date = :date')
            ->setParameter('areaId', $commonArea->getId())
            ->setParameter('date', $date->setTime(0,0,0));

        if ($hour !== null) {
            $qb->andWhere('r.hour = :hour')
                ->setParameter('hour', $hour);
        }

        /** @var ReservationEntity[] $entities */
        $entities = $qb->getQuery()->getResult();

        return array_map(fn(ReservationEntity $e) => new Reservation(
            $commonArea,
            $e->getDate(),
            $e->getHour()
        ), $entities);
    }

    /**
     * Guarda una reserva
     */
    public function save(Reservation $reservation): void
    {
        $entity = new ReservationEntity();
        $entity->setCommonAreaId($reservation->getCommonArea()->getId());
        $entity->setDate($reservation->getDate());
        $entity->setHour($reservation->getHour());
        $entity->setStatus($reservation->getStatus()->value);

        $this->em->persist($entity);
        $this->em->flush();
    }
}
