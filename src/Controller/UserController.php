<?php

namespace App\Controller;

use App\DTO\CheckUserData;
use App\DTO\RegisterUserData;
use App\Exception\EntityException\EntityExistsException;
use App\Repository\UserRepositoryInterface;
use App\Services\UserService;
use App\VO\ApiErrorCode;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-api/v1")
 */
class UserController extends AbstractController
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @Route("/user", methods={"GET"})
     *
     * @param CheckUserData $checkUserData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws EntityNotFoundException
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
            throw new EntityNotFoundException(
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
     * @throws EntityExistsException
     * @throws NonUniqueResultException
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
            throw new EntityExistsException(
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
