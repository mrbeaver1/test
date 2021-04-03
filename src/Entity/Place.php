<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @ORM\Table(name="place")
 */
class Place
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Flight
     *
     * @ORM\ManyToOne(targetEntity="Flight", inversedBy="places")
     * @ORM\JoinColumn(name="flight_id", referencedColumnName="id")
     */
    private $flight;

    /**
     * @var Ticket | null
     *
     * @ORM\OneToOne(targetEntity="Ticket", mappedBy="place")
     */
    private $ticket;

    /**
     * @var Reservation | null
     *
     * @ORM\OneToOne(targetEntity="Reservation", mappedBy="place")
     */
    private $reservation;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="number")
     */
    private $placeNumber;

    /**
     * @param Flight             $flight
     * @param int                $placeNumber
     * @param Reservation | null $reservation
     * @param Ticket | null      $ticket
     */
    public function __construct(
        Flight $flight,
        int $placeNumber,
        ?Reservation $reservation = null,
        ?Ticket $ticket = null
    ) {
        $this->flight = $flight;
        $this->ticket = $ticket;
        $this->reservation = $reservation;
        $this->placeNumber = $placeNumber;
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
     * @return Ticket | null
     */
    public function getTicket(): ?Ticket
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

    /**
     * @return Reservation | null
     */
    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    /**
     * @param Reservation | null $reservation
     *
     * @return Place
     */
    public function updateReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * @return int
     */
    public function getPlaceNumber(): int
    {
        return $this->placeNumber;
    }

    /**
     * @param int $placeNumber
     *
     * @return Place
     */
    public function updatePlaceNumber(int $placeNumber): self
    {
        $this->placeNumber = $placeNumber;

        return $this;
    }
}
