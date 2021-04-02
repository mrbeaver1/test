<?php

namespace App\Validators;

use App\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFlightDataValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'number' => $this->getNumberRules(),
            'date' => $this->getDateRules(),
            'places_count' => $this->getPlacesCountRules(),
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
    private function getNumberRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Type([
                'type' => 'integer',
                'message' => 'Значение должно быть целым числом',
            ]),
        ];
    }

    /**
     * @return array
     */
    private function getDateRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\DateTime(['format' => 'd.m.Y']),
        ];
    }

    /**
     * @return array
     */
    private function getPlacesCountRules(): array
    {
        return [
            new Assert\Range([
                'min' => 150,
                'max' => 150,
                'minMessage' => 'ID не может быть меньше 150',
                'maxMessage' => 'ID не может быть более 150',
            ]),
            $this->getNumberRules(),
        ];
    }
}
