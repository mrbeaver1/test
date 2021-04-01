<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Place
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @var Flight
     *
     * @ORM\ManyToOne(targetEntity="Flight")
     * @ORM\JoinColumn(name="Flight_id", referencedColumnName="id")
     */
    private Flight $flight;

    /**
     * @param Flight $flight
     */
    public function __construct(Flight $flight)
    {
        $this->flight = $flight;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Flight
     */
    public function getFlight(): Flight
    {
        return $this->flight;
    }

    /**
     * @param Flight $flight
     *
     * @return Place
     */
    public function updateFlight(Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }
}
