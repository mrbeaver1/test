<?php

namespace App\Repository;

use App\Entity\User;
use App\VO\Email;
use DateTimeImmutable;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;

interface UserRepositoryInterface
{
    /**
     * @param string            $passportSeries
     * @param string            $passportNumber
     * @param string            $passportDivisionName
     * @param string            $passportDivisionCode
     * @param DateTimeImmutable $passportIssueDate
     * @param string | null     $firstName
     * @param string | null     $lastName
     * @param string | null     $middleName
     *
     * @return User | null
     *
     * @throws NonUniqueResultException
     */
    public function findByPassport(
        string $passportSeries,
        string $passportNumber,
        string $passportDivisionName,
        string $passportDivisionCode,
        DateTimeImmutable $passportIssueDate,
        ?string $firstName,
        ?string $lastName,
        ?string $middleName
    ): ?User;

    /**
     * @param int $id
     *
     * @return User
     *
     * @throws EntityNotFoundException
     */
    public function getById(int $id): User;
}
