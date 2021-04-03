<?php

namespace App\Validators;

use App\Validation\AbstractValidator;

class TicketIdValidator extends AbstractValidator
{
    /**
     * Возвращает список полей с правилами валидации
     *
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'ticket_id' => $this->getIdRules(),
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
