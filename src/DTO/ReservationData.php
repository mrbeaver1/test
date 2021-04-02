<?php

namespace App\DTO;

class ReservationData
{
    /**
     * @var int
     */
    private $placeNumber;

    /**
     * @param int $placeNumber
     */
    public function __construct(int $placeNumber)
    {
        $this->placeNumber = $placeNumber;
    }

    /**
     * @return int
     */
    public function getPlaceNumber(): int
    {
        return $this->placeNumber;
    }
}
