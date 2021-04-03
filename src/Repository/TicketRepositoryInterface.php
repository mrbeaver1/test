<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\ORM\EntityNotFoundException;

interface TicketRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return Ticket
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): Ticket;
}