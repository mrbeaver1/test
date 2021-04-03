<?php

namespace App\Controller;

use App\DTO\CheckUserData;
use App\DTO\RegisterUserData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\Services\UserService;
use App\VO\ApiErrorCode;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-api/v1")
 */
class UserController extends BaseApiController implements UserControllerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserService             $userService
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserService $userService
    ) {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    /**
     * @Route("/user", methods={"GET"})
     *
     * @param CheckUserData $checkUserData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiNotFoundException
     */
    public function showUser(CheckUserData $checkUserData): JsonResponse
    {
        $user = $this->userRepository->findByPassport(
            $checkUserData->getPassportSeries(),
            $checkUserData->getPassportNumber(),
            $checkUserData->getPassportDivisionName(),
            $checkUserData->getPassportDivisionCode(),
            $checkUserData->getPassportIssueDate(),
            $checkUserData->getFirstName(),
            $checkUserData->getLastName(),
            $checkUserData->getMiddleName()
        );

        if (is_null($user)) {
            throw new ApiNotFoundException(
                ['Юзер с такими паспортными данными не существует в системе'],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => $user->toArray(),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/user", methods={"POST"})
     *
     * @param RegisterUserData $registerUserData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     */
    public function registerUser(RegisterUserData $registerUserData): JsonResponse
    {
        $passport = $registerUserData->getPassport();

        $user = $this->userRepository->findByPassport(
            $passport->getPassportSeries(),
            $passport->getPassportNumber(),
            $passport->getPassportDivisionName(),
            $passport->getPassportDivisionCode(),
            $passport->getPassportIssueDate(),
            $passport->getFirstName(),
            $passport->getLastName(),
            $passport->getMiddleName()
        );

        if (!is_null($user)) {
            throw new ApiBadRequestException(
                ['Юзер с такими паспортными данными уже существует в системе'],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => [
                'id' => $this->userService->registerUser($registerUserData->getEmail(), $passport)->getId(),
            ],
        ], JsonResponse::HTTP_CREATED);
    }
}
