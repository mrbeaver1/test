<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Flight
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
     * @var Collection | Place[]
     *
     * @ORM\OneToMany(targetEntity="Place", mappedBy="flight")
     */
    private Collection $places;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $departure;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_active", options={"default" : true})
     */
    private bool $isActive;

    /**
     * @param array | Place[] $places
     * @param DateTimeImmutable  $departure
     */
    public function __construct(DateTimeImmutable $departure, array $places = [])
    {
        $this->places = new ArrayCollection(array_unique($places, SORT_REGULAR));
        $this->departure = $departure;
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
}
