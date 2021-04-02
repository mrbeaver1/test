<?php

namespace App\ArgumentResolvers;

use App\Validators\CheckUserDataValidator;
use DateTimeImmutable;
use Exception;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use App\DTO\CheckUserData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\VO\ApiErrorCode;
use Generator;

class CheckUserDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @var CheckUserDataValidator
     */
    private CheckUserDataValidator $validator;

    /**
     * @param CheckUserDataValidator $validator
     */
    public function __construct(CheckUserDataValidator $validator)
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
        return CheckUserData::class === $argument->getType();
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
        $passportSeries = $request->get('passport_series');
        $passportNumber = $request->get('passport_number');
        $passportDivisionName = $request->get('passport_division_name');
        $passportDivisionCode = $request->get('passport_division_code');
        $passportIssueDate = $request->get('passport_issue_date');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $middleName = $request->get('middle_name');

        $errors = $this->validator->validate([
            'passport_series' => $passportSeries,
            'passport_number' => $passportNumber,
            'passport_division_name' => $passportDivisionName,
            'passport_division_code' => $passportDivisionCode,
            'passport_issue_date' => $passportIssueDate,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
        ]);

        if (!empty($errors)) {
            throw new ApiBadRequestException($errors, new ApiErrorCode(ApiErrorCode::VALIDATION_ERROR));
        }

        yield new CheckUserData(
            $passportSeries,
            $passportNumber,
            $passportDivisionName,
            $passportDivisionCode,
            new DateTimeImmutable($passportIssueDate),
            $firstName,
            $lastName,
            $middleName
        );
    }
}
