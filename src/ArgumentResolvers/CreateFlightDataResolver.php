<?php

namespace App\ArgumentResolvers;

use App\DTO\CreateFlightData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Validators\CreateFlightDataValidator;
use App\VO\ApiErrorCode;
use DateTimeImmutable;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreateFlightDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var CreateFlightDataValidator
     */
    private $validator;

    /**
     * @param CreateFlightDataValidator $validator
     */
    public function __construct(CreateFlightDataValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return CreateFlightData::class === $argument->getType();
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     *
     * @throws Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $params = json_decode($request->getContent(), true);

        $number = $params['number'] ?? null;
        $date = $params['date'] ?? null;
        $placesCount = $params['places_count'] ?? null;

        $errors = $this->validator->validate([
            'number' => $number,
            'date' => $date,
            'places_count' => $placesCount,
        ]);

        if (!empty($errors)) {
            throw new ApiBadRequestException(
                $errors,
                new ApiErrorCode(ApiErrorCode::VALIDATION_ERROR)
            );
        }

        yield new CreateFlightData(
            $number,
            new DateTimeImmutable($date),
            $placesCount
        );
    }
}
