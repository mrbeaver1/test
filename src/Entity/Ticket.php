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

    private User $owner;

    private Flight $flight;
}