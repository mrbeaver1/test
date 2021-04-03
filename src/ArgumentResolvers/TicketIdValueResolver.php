<?php

namespace App\ArgumentResolvers;

use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Validators\TicketIdValidator;
use App\VO\ApiErrorCode;
use App\VO\TicketId;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class TicketIdValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var TicketIdValidator
     */
    private $validator;

    /**
     * @param TicketIdValidator $validator
     */
    public function __construct(TicketIdValidator $validator)
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
        return TicketId::class === $argument->getType();
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
        $ticketId = $request->get('ticket_id');

        $errors = $this->validator->validate(['ticket_id' => $ticketId]);

        if (!empty($errors)) {
            throw new ApiNotFoundException($errors, new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND));
        }

        yield new TicketId($ticketId);
    }
}
