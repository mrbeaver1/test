<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

class Reservation
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
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", name="created_at")
     */
    private $createdAt;

    /**
     * @var Place
     *
     * @ORM\OneToOne(targetEntity="Place", mappedBy="reservation")
     */
    private $place;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @param Place $place
     * @param User  $owner
     */
    public function __construct(Place $place, User $owner)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->place = $place;
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Place
     */
    public function getPlace(): Place
    {
        return $this->place;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'owner' => $this->getOwner(),
            'place' => $this->getPlace(),
            'created_at' => $this->getCreatedAt()->format(DateTimeImmutable::ATOM),
        ];
    }
}
