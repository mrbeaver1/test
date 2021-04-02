<?php

namespace App\DTO;

use App\VO\Email;
use DateTimeImmutable;

class CheckUserData
{
    /**
     * @var string
     */
    private string $passportSeries;

    /**
     * @var string
     */
    private string $passportNumber;

    /**
     * @var string
     */
    private string $passportDivisionName;

    /**
     * @var string
     */
    private string $passportDivisionCode;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $passportIssueDate;

    /**
     * @var string | null
     */
    private ?string $firstName;

    /**
     * @var string | null
     */
    private ?string $lastName;

    /**
     * @var string | null
     */
    private ?string $middleName;

    /**
     * @param string            $passportSeries
     * @param string            $passportNumber
     * @param string            $passportDivisionName
     * @param string            $passportDivisionCode
     * @param DateTimeImmutable $passportIssueDate
     * @param string | null     $firstName
     * @param string | null     $lastName
     * @param string | null     $middleName
     */
    public function __construct(
        string $passportSeries,
        string $passportNumber,
        string $passportDivisionName,
        string $passportDivisionCode,
        DateTimeImmutable $passportIssueDate,
        ?string $firstName,
        ?string $lastName,
        ?string $middleName
    ) {
        $this->passportSeries = $passportSeries;
        $this->passportNumber = $passportNumber;
        $this->passportDivisionName = $passportDivisionName;
        $this->passportDivisionCode = $passportDivisionCode;
        $this->passportIssueDate = $passportIssueDate;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getPassportSeries(): string
    {
        return $this->passportSeries;
    }

    /**
     * @return string
     */
    public function getPassportNumber(): string
    {
        return $this->passportNumber;
    }

    /**
     * @return string
     */
    public function getPassportDivisionName(): string
    {
        return $this->passportDivisionName;
    }

    /**
     * @return string
     */
    public function getPassportDivisionCode(): string
    {
        return $this->passportDivisionCode;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getPassportIssueDate(): DateTimeImmutable
    {
        return $this->passportIssueDate;
    }

    /**
     * @return string | null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string | null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string | null
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }
}
