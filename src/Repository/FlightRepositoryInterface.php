<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

interface FlightRepositoryInterface
{
    /**
     * @param int $number
     *
     * @return Flight | null
     *
     * @throws NonUniqueResultException
     */
    public function findByFlightNumber(int $number): ?Flight;

    /**
     * @param int $id
     *
     * @return Flight
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Flight;
}
