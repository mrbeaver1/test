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
    private int $id;

    /**
     * @var User
     */
    private User $owner;

    /**
     * @var Flight
     */
    private Flight $flight;
}