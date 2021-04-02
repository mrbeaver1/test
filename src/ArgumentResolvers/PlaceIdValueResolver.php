<?php


namespace App\ArgumentResolvers;


use App\Exception\ApiHttpException\ApiNotFoundException;
use App\VO\ApiErrorCode;
use App\VO\PlaceId;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PlaceIdValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var PlaceIdValidator
     */
    private $validator;

    /**
     * @param PlaceIdValidator $validator
     */
    public function __construct(PlaceIdValidator $validator)
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
        return PlaceId::class === $argument->getType();
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
        $placeId = $request->get('place_id');

        $errors = $this->validator->validate(['place_id' => $placeId]);

        if (!empty($errors)) {
            throw new ApiNotFoundException($errors, new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND));
        }

        yield new PlaceId($placeId);
    }
}
