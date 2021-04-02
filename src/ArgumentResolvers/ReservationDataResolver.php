<?php

namespace App\ArgumentResolvers;

use App\DTO\ReservationData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Validators\ReservationDataValidator;
use App\VO\ApiErrorCode;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ReservationDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ReservationDataValidator
     */
    private $validator;

    /**
     * @param ReservationDataValidator $validator
     */
    public function __construct(ReservationDataValidator $validator)
    {
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return ReservationData::class === $argument->getType();
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

        yield new ReservationData($placeNumber);
    }
}