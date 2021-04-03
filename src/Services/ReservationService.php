<?php

namespace App\Services;

use App\Entity\Flight;
use App\Entity\Place;
use App\Entity\Reservation;
use App\Entity\User;
use App\Exception\EntityException\EntityExistsException;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface  $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User  $user
     * @param Place $place
     *
     * @return Reservation
     *
     * @throws EntityExistsException
     */
    public function placeReservation(User $user, Place $place): Reservation
    {
        if (!empty($place->getTicket()) || !empty($place->getReservation())) {
            throw new EntityExistsException('Данное место уже куплено или забронировано');
        }

        $reservation = new Reservation($place, $user);

        $this->em->persist($reservation);

        $place->updateReservation($reservation);

        $this->em->flush();

        return $reservation;
    }

    /**
     * @param User         $user
     * @param Place        $place
     * @param Reservation $reservation
     *
     * @return void
     */
    public function cancelReservation(User $user, Place $place, Reservation $reservation): void
    {
        $user->getReservations()->removeElement(
            $user->getReservations()->filter(
                static function (Reservation $userReservation) use ($reservation): bool {
                    return $userReservation->getId() === $reservation->getId();
                }
            )
        );

        $place->updateReservation(null);

        $this->em->remove($reservation);
        $this->em->flush();
    }
}
