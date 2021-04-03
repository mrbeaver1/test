<?php

namespace App\Validators;

use App\Validation\AbstractValidator;
use App\VO\Event;
use Symfony\Component\Validator\Constraints as Assert;

class CallBackDataValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'flight_id' => $this->getIdRules(),
            'triggered_at' => $this->getTriggeredAtRules(),
            'event' => $this->getEventRules(),
            'secret_key' => $this->getSecretKeyRules(),
        ];
    }

    /**
     * @return array
     */
    protected function getOptionalFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    private function getTriggeredAtRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'Значение должно быть строкой'
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getEventRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Choice([
                'value' => Event::VALID_EVENTS,
                'message' => 'Недопустимое значение события',
            ]),
        ];

    }

    /**
     * @return array
     */
    private function getSecretKeyRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'Значение должно быть строкой'
            ]),
        ];
    }
}
