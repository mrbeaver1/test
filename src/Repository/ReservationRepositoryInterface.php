<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

interface ReservationRepositoryInterface
{
    /**
     * @param int $reservationId
     * @param int $userId
     *
     * @return Reservation
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getByReservationIdAndUserId(int $reservationId, int $userId): Reservation;

    /**
     * @param int $id
     *
     * @return Reservation
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Reservation;
}
