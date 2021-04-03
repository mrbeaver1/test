<?php

namespace App\ArgumentResolvers;

use App\DTO\RegisterUserData;
use App\DTO\Passport;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Validators\RegisterUserDataValidator;
use App\VO\ApiErrorCode;
use App\VO\Email;
use DateTimeImmutable;
use Exception;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RegisterUserDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var RegisterUserDataValidator
     */
    private $validator;

    /**
     * @param RegisterUserDataValidator $validator
     */
    public function __construct(RegisterUserDataValidator $validator)
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
        return RegisterUserData::class === $argument->getType();
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
        $params = json_decode($request->getContent(), true);
        $email = $params['email'] ?? null;
        $passport = $params['passport'] ?? null;

        $errors = $this->validator->validate(['email' => $email, 'passport' => $passport]);

        if (!empty($errors)) {
            throw new ApiBadRequestException(
                $errors,
                new ApiErrorCode(ApiErrorCode::VALIDATION_ERROR)
            );
        }

        yield new RegisterUserData(
            new Email($email),
            new Passport(
                $passport['series'],
                $passport['number'],
                $passport['division_name'],
                $passport['division_code'],
                new DateTimeImmutable($passport['issue_date']),
                $passport['first_name'],
                $passport['last_name'],
                $passport['middle_name']
            )
        );
    }
}
