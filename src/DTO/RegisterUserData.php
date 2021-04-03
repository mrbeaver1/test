<?php

namespace App\DTO;

use App\VO\Email;

class RegisterUserData
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var Passport
     */
    private $passport;

    /**
     * @param Email    $email
     * @param Passport $passport
     */
    public function __construct(Email $email, Passport $passport)
    {
        $this->email = $email;
        $this->passport = $passport;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Passport
     */
    public function getPassport(): Passport
    {
        return $this->passport;
    }
}
