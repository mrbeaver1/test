<?php

namespace App\Entity;

use App\DTO\Passport;
use App\VO\Email;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
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
     * @var Collection | Reservation[]
     *
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="owner")
     */
    private Collection $reservations;

    /**
     * @var Email
     *
     * @ORM\Embedded(class="App\VO\Email")
     */
    private Email $email;

    /**
     * @var Collection | Ticket[]
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="owner")
     */
    private Collection $tickets;

    /**
     * @var Passport
     *
     * @ORM\Embedded(class="App\DTO\Passport")
     */
    private Passport $passport;

    /**
     * @param Email                 $email
     * @param Passport              $passport
     * @param array | Reservation[] $reservations
     * @param array | Ticket[]      $tickets
     */
    public function __construct(
        Email $email,
        Passport $passport,
        array $reservations = [],
        array $tickets = []
    ) {
        $this->reservations = new ArrayCollection(array_unique($reservations, SORT_REGULAR));
        $this->email = $email;
        $this->tickets = new ArrayCollection(array_unique($tickets, SORT_REGULAR));
        $this->passport = $passport;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection | Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * @param Reservation $reservation
     *
     * @return User
     */
    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
        }

        return $this;
    }

    /**
     * @param Email $email
     *
     * @return User
     */
    public function updateEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Collection | Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    /**
     * @param Ticket $ticket
     *
     * @return User
     */
    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
        }

        return $this;
    }

    /**
     * @return Passport
     */
    public function getPassport(): Passport
    {
        return $this->passport;
    }

    /**
     * @param Passport $passport
     *
     * @return User
     */
    public function updatePassport(Passport $passport): self
    {
        $this->passport = $passport;

        return $this;
    }
//Методы для имплементации интерфейса
    public function getRoles()
    {

    }

    public function getSalt()
    {

    }

    public function getUsername()
    {

    }

    public function eraseCredentials()
    {

    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }
}
