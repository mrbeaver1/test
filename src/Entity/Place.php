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
     * @var Ticket
     */
    private Ticket $ticket;

    /**
     * @param Flight $flight
     * @param Ticket $ticket
     */
    public function __construct(Flight $flight, Ticket $ticket)
    {
        $this->flight = $flight;
        $this->ticket = $ticket;
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

    /**
     * @return Ticket
     */
    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    /**
     * @param Ticket $ticket
     *
     * @return Place
     */
    public function updateTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}
