<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

interface PlaceRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return Place
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Place;

    /**
     * @param int $flightId
     * @param int $number
     *
     * @return Place
     *
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function getByNumberAndFlightId(int $flightId, int $number): Place;
}
