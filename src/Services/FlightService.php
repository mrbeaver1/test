<?php

namespace App\Services;

use App\Entity\Flight;
use App\Entity\Place;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class FlightService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface  $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int               $number
     * @param DateTimeImmutable $date
     * @param int               $placesCount
     *
     * @return Flight
     */
    public function create(int $number, DateTimeImmutable $date, int $placesCount): Flight
    {
        $flight = new Flight($number, $date);

        $this->em->persist($flight);

        for ($i = 1; $i <= $placesCount; $i++) {
            $place = new Place($flight, $i);

            $this->em->persist($place);
        }

        $this->em->flush();

        return $flight;
    }
}
