<?php

namespace App\Validators;

use App\Validation\AbstractValidator;
use Symfony\Component\Validator\Constraints as Assert;

class PlaceNumberDataValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getConstraints(): array
    {
        return [
            'place_number' => $this->getNumberRules(),
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
            new Assert\Range([
                'min' => 1,
                'max' => 150,
                'minMessage' => 'ID не может быть меньше 1',
                'maxMessage' => 'ID не может быть более 150',
            ]),
            new Assert\Type([
                'type' => 'integer',
                'message' => 'Значение должно быть целым числом',
            ]),
        ];
    }
}
