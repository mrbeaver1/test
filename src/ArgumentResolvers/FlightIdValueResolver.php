<?php


namespace App\ArgumentResolvers;


use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Validators\FlightIdValidator;
use App\VO\ApiErrorCode;
use App\VO\FlightId;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FlightIdValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var FlightIdValidator
     */
    private $validator;

    /**
     * @param FlightIdValidator $validator
     */
    public function __construct(FlightIdValidator $validator)
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
        return FlightId::class === $argument->getType();
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
        $flightId = $request->get('flight_id');

        $errors = $this->validator->validate(['flight_id' => $flightId]);

        if (!empty($errors)) {
            throw new ApiNotFoundException($errors, new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND));
        }

        yield new FlightId($flightId);
    }
}
