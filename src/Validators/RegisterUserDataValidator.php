<?php

namespace App\Validators;

use App\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDataValidator extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'email' => $this->getEmailRules(),
            'passport' => $this->getPassportRules(),
        ];
    }

    /**
     * Возвращает список необязательных полей
     *
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }

    /**
     * Возвращает правила валидации для данных введённых в строку поиска
     *
     * @return array
     */
    private function getEmailRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Email([
                'message' => 'Недопустимое значение e-mail',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getPassportRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Collection([
                'fields' => [
                    'series' => [
                        $this->getNotBlank(),
                        new Assert\Regex([
                            'pattern' => '/[0-9]{4}/',
                            'message' => 'Серия паспорта состоит из 4 цифр',
                        ]),
                    ],
                    'number' => [
                        $this->getNotBlank(),
                        new Assert\Regex([
                            'pattern' => '/[0-9]{6}/',
                            'message' => 'Номер паспорта состоит из 6 цифр',
                        ]),
                    ],
                    'division_name' => [
                        $this->getNotBlank(),
                        new Assert\Type([
                            'type' => 'string',
                            'message' => 'Недопустимый тип. Ожидалась строка',
                        ]),
                    ],
                    'division_code' => [
                        $this->getNotBlank(),
                        $this->getNotBlank(),
                        new Assert\Regex([
                            'pattern' => '/[0-9]{6}/',
                            'message' => 'Код подразделения состоит из 6 цифр',
                        ]),
                    ],
                    'issue_date' => [
                        $this->getNotBlank(),
                        new Assert\DateTime(['format' => 'd.m.Y']),
                    ],
                    'first_name' => [
                        $this->getNotBlank(),
                        new Assert\Type([
                            'type' => 'string',
                            'message' => 'Недопустимый тип. Ожидалась строка',
                        ]),
                    ],
                    'last_name' => [
                        $this->getNotBlank(),
                        new Assert\Type([
                            'type' => 'string',
                            'message' => 'Недопустимый тип. Ожидалась строка',
                        ]),
                    ],
                    'middle_name' => [
                        $this->getNotBlank(),
                        new Assert\Type([
                            'type' => 'string',
                            'message' => 'Недопустимый тип. Ожидалась строка',
                        ]),
                    ],
                ],
            ])
        ];
    }
}
