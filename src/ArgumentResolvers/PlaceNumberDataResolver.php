<?php

namespace App\ArgumentResolvers;

use App\DTO\PlaceNumberData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Validators\PlaceNumberDataValidator;
use App\VO\ApiErrorCode;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PlaceNumberDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var PlaceNumberDataValidator
     */
    private $validator;

    /**
     * @param PlaceNumberDataValidator $validator
     */
    public function __construct(PlaceNumberDataValidator $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return PlaceNumberData::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $params = json_decode($request->getContent(), true);

        $placeNumber = $params['place_number'] ?? null;

        $errors = $this->validator->validate(['place_number' => $placeNumber]);

        if (!empty($errors)) {
            throw new ApiBadRequestException(
                $errors,
                new ApiErrorCode(ApiErrorCode::VALIDATION_ERROR)
            );
        }

        yield new PlaceNumberData($placeNumber);
    }
}