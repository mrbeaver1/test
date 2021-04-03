<?php

namespace App\DTO;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Passport
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", name="series")
     */
    private $passportSeries;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="number")
     */
    private $passportNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="division")
     */
    private $passportDivisionName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="division_code")
     */
    private $passportDivisionCode;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", name="issue_date")
     */
    private $passportIssueDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="last_name")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="middle_name")
     */
    private $middleName;

    /**
     * @param string            $passportSeries
     * @param string            $passportNumber
     * @param string            $passportDivisionName
     * @param string            $passportDivisionCode
     * @param DateTimeImmutable $passportIssueDate
     * @param string            $firstName
     * @param string            $lastName
     * @param string            $middleName
     */
    public function __construct(
        string $passportSeries,
        string $passportNumber,
        string $passportDivisionName,
        string $passportDivisionCode,
        DateTimeImmutable $passportIssueDate,
        string $firstName,
        string $lastName,
        string $middleName
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
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $issueDate = $this->passportIssueDate;

        return [
            'series' => empty($this->passportSeries) ? null : $this->getPassportSeries(),
            'number' => empty($this->passportNumber) ? null : $this->getPassportNumber(),
            'division_name' => empty($this->passportDivisionName) ? null : $this->getPassportDivisionName(),
            'division_code' => empty($this->passportDivisionCode) ? null : $this->getPassportDivisionCode(),
            'issue_date' => is_null($issueDate) ? null : $issueDate->format(DateTimeImmutable::ATOM),
            'first_name' => empty($this->firstName) ? null : $this->getFirstName(),
            'last_name' => empty($this->lastName) ? null : $this->getLastName(),
            'middle_name' => empty($this->middleName) ? null : $this->getMiddleName(),
        ];
    }
}
