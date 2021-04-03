<?php

namespace App\DTO;

use DateTimeImmutable;

class CreateFlightData
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var int
     */
    private $placesCount;

    /**
     * @param int               $number
     * @param DateTimeImmutable $date
     * @param int               $placesCount
     */
    public function __construct(int $number, DateTimeImmutable $date, int $placesCount)
    {
        $this->number = $number;
        $this->date = $date;
        $this->placesCount = $placesCount;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getPlacesCount(): int
    {
        return $this->placesCount;
    }
}
