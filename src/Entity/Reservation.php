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
    private int $id;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $createdAt;

    /**
     * @var Flight
     */
    private Flight $flight;

    /**
     * @var Place
     *
     * @ORM\OneToOne(targetEntity="Place", mappedBy="reservation")
     */
    private Place $place;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private User $owner;
}
