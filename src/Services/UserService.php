<?php

namespace App\Services;

use App\DTO\Passport;
use App\Entity\User;
use App\VO\Email;
use Doctrine\ORM\EntityManagerInterface;

class UserService
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
     * @param Email    $email
     * @param Passport $passport
     *
     * @return User
     */
    public function registerUser(Email $email, Passport $passport): User
    {
        $user = new User($email, $passport);

        $this->em->persist($user);

        $this->em->flush();

        return $user;
    }
}
