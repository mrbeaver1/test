<?php

namespace App\ArgumentResolvers;

use App\DTO\CallBackData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Validators\CallBackDataValidator;
use App\VO\ApiErrorCode;
use App\VO\Event;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CallBackDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var CallBackDataValidator
     */
    private $validator;

    /**
     * @param CallBackDataValidator $validator
     */
    public function __construct(CallBackDataValidator $validator)
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
        return CallBackData::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $params = json_decode($request->getContent(), true);

        $flightId = $params['flight_id'] ?? null;
        $triggeredAt = $params['triggered_at'] ?? null;
        $event = $params['event'] ?? null;
        $secretKey = $params['secret_key'] ?? null;

        $errors = $this->validator->validate([
            'flight_id' => $flightId,
            'triggered_at' => $triggeredAt,
            'event' => $event,
            'secret_key' => $secretKey,
        ]);

        if (!empty($errors)) {
            throw new ApiBadRequestException(
                $errors,
                new ApiErrorCode(ApiErrorCode::VALIDATION_ERROR)
            );
        }

        yield new CallBackData(
            $flightId,
            $triggeredAt,
            new Event($event),
            $secretKey
        );
    }
}
