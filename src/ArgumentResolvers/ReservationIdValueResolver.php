<?php

namespace App\ArgumentResolvers;

use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Validators\ReservationIdValidator;
use App\VO\ApiErrorCode;
use App\VO\ReservationId;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ReservationIdValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ReservationIdValidator
     */
    private $validator;

    /**
     * @param ReservationIdValidator $validator
     */
    public function __construct(ReservationIdValidator $validator)
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
        return ReservationId::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     *
     * @throws Exception
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $reservationId = $request->get('reservation_id');

        $errors = $this->validator->validate(['reservation_id' => $reservationId]);

        if (!empty($errors)) {
            throw new ApiNotFoundException($errors, new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND));
        }

        yield new ReservationId($reservationId);
    }
}
