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
}