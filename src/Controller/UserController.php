<?php

namespace App\Controller;

use App\DTO\CheckUserData;
use App\Exception\EntityException\EntityExistsException;
use App\Repository\UserRepositoryInterface;
use App\VO\ApiErrorCode;
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

        if (!empty($user)) {
            throw new EntityExistsException(
                ['Юзер с такими паспортными данными уже существует в системе'],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => $user->toArray(),
        ], JsonResponse::HTTP_OK);
    }
}
