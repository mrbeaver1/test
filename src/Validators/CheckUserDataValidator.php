<?php

namespace App\Validators;

use App\Validation\AbstractValidator;
use App\Validation\Constraints\LessThanDate;
use Symfony\Component\Validator\Constraints as Assert;

class CheckUserDataValidator extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'passport_series' => $this->getPassportSeriesRules(),
            'passport_number' => $this->getPassportNumberRules(),
            'passport_division_name' => $this->getPassportDivisionNameRules(),
            'passport_division_code' => $this->getPassportDivisionCodeRules(),
            'passport_issue_date' => $this->getPassportIssueDateRules(),
            'first_name' => $this->getStringRules(),
            'last_name' => $this->getStringRules(),
            'middle_name' => $this->getStringRules(),
        ];
    }

    /**
     * Возвращает список необязательных полей
     *
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [
            'first_name',
            'last_name',
            'middle_name',
        ];
    }

    /**
     * @return array
     */
    private function getPassportSeriesRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Regex([
                'pattern' => '/[0-9]{4}/',
                'message' => 'Серия паспорта состоит из 4 цифр',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getPassportNumberRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Regex([
                'pattern' => '/[0-9]{6}/',
                'message' => 'Номер паспорта состоит из 6 цифр',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getPassportDivisionNameRules(): array
    {
        return [
            $this->getNotBlank(),
            $this->getStringRules(),
        ];
    }

    /**
     * @return array
     */
    private function getPassportDivisionCodeRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Regex([
                'pattern' => '/[0-9]{6}/',
                'message' => 'Код подразделения состоит из 6 цифр',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getPassportIssueDateRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\DateTime(['format' => 'd.m.Y']),
        ];
    }

    /**
     * @return array
     */
    private function getStringRules(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
                'message' => 'Недопустимый тип. Ожидалась строка',
            ]),
        ];
    }
}
