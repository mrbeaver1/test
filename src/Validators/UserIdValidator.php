<?php

namespace App\Validators;

use App\Validation\AbstractValidator;

class UserIdValidator extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'user_id' => $this->getIdRules(),
        ];
    }

    /**
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }
}
