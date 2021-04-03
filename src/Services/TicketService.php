<?php

namespace App\Services;

use App\Entity\Flight;
use App\Entity\Place;
use App\Entity\Ticket;
use App\Entity\User;
use App\Exception\EntityException\EntityExistsException;
use Doctrine\ORM\EntityManagerInterface;

class TicketService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface  $em
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @param User   $user
     * @param Flight $flight
     * @param Place  $place
     *
     * @return Ticket
     *
     * @throws EntityExistsException
     */
    public function createTicket(User $user, Flight $flight, Place $place): Ticket
    {
        if (!empty($place->getTicket())) {
            throw new EntityExistsException('Билет на данное место уже продан');
        } elseif (!empty($place->getReservation()) && $place->getReservation()->getOwner() !== $user) {
            throw new EntityExistsException('Данное место забронировано другим человеком');
        }

        $ticket = new Ticket($user, $flight, $place);

        $this->em->persist($ticket);

        $place->updateTicket($ticket);

        $user->addTicket($ticket);

        $this->em->flush();

        return $ticket;
    }

    /**
     * @param User   $user
     * @param Flight $flight
     * @param Place  $place
     * @param Ticket $ticket
     *
     * @return void
     */
    public function returnTicket(User $user, Flight $flight, Place $place, Ticket $ticket): void
    {
        $user->getTickets()->removeElement(
            $user->getTickets()->filter(
                static function (Ticket $userTicket) use ($ticket): bool {
                    return $userTicket->getId() === $ticket->getId();
                }
            )
        );

        $this->em->remove($ticket);

        $this->em->flush();
    }
}
