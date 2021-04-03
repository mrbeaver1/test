<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\ORM\EntityNotFoundException;

interface ReservationRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return Reservation
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Reservation;
}
