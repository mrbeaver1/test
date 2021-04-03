<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 * @ORM\Table(name="flight")
 */
class Flight
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
     * @var Collection | Place[]
     *
     * @ORM\OneToMany(targetEntity="Place", mappedBy="flight")
     */
    private $places;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="flight_number", unique=true)
     */
    private $flightNumber;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private $departure;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_active", options={"default" : true})
     */
    private $isActive;

    /**
     * @var Collection | Ticket[]
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="flight")
     */
    private $tickets;

    /**
     * @param int               $flightNumber
     * @param DateTimeImmutable $departure
     * @param array | Place[]   $places
     * @param array | Ticket[]  $tickets
     */
    public function __construct(
        int $flightNumber,
        DateTimeImmutable $departure,
        array $places = [],
        array $tickets = []
    ) {
        $this->places = new ArrayCollection(array_unique($places, SORT_REGULAR));
        $this->tickets = new ArrayCollection(array_unique($tickets, SORT_REGULAR));
        $this->departure = $departure;
        $this->flightNumber = $flightNumber;
        $this->isActive = true;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection | Place[]
     */
    public function getPlaces():Collection
    {
        return $this->places;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDeparture(): DateTimeImmutable
    {
        return $this->departure;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param Place $place
     *
     * @return Flight
     */
    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
        }

        return $this;
    }

    /**
     * @param DateTimeImmutable $departure
     *
     * @return Flight
     */
    public function updateDeparture(DateTimeImmutable $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * @param bool $isActive
     *
     * @return Flight
     */
    public function updateIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return int
     */
    public function getFlightNumber(): int
    {
        return $this->flightNumber;
    }

    /**
     * @param int $flightNumber
     *
     * @return Flight
     */
    public function setFlightNumber(int $flightNumber): self
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }
}
