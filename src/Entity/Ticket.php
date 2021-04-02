<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Ticket
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var Flight
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $flight;

    /**
     * @var Place
     *
     * @ORM\OneToOne(targetEntity="Place", mappedBy="ticket")
     */
    private $place;

    /**
     * @param User   $owner
     * @param Flight $flight
     * @param Place  $place
     */
    public function __construct(User $owner, Flight $flight, Place $place)
    {
        $this->owner = $owner;
        $this->flight = $flight;
        $this->place = $place;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return Flight
     */
    public function getFlight(): Flight
    {
        return $this->flight;
    }

    /**
     * @return Place
     */
    public function getPlace(): Place
    {
        return $this->place;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'flight' => $this->getFlight(),
            'place' => $this->getPlace(),
            'owner' => $this->getOwner(),
        ];
    }
}
