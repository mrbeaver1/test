<?php

namespace App\Controller;

use App\DTO\ReservationData;
use App\Entity\Reservation;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Exception\EntityException\EntityExistsException;
use App\Repository\FlightRepositoryInterface;
use App\Repository\PlaceRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Services\ReservationService;
use App\VO\ApiErrorCode;
use App\VO\FlightId;
use App\VO\ReservationId;
use App\VO\UserId;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/vi")
 */
class ApiController extends AbstractController
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var FlightRepositoryInterface
     */
    private $flightRepository;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var ReservationService
     */
    private $reservationService;

    /**
     * @var ReservationRepositoryInterface
     */
    private $reservationRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("user/{user_id}/flight/{flight_id}/place/reservation", methods={"POST"})
     *
     * @param UserId          $userId
     * @param FlightId        $flightId
     * @param ReservationData $reservationData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     */
    public function placeReservation(
        UserId $userId,
        FlightId $flightId,
        ReservationData $reservationData
    ): JsonResponse {
        try {
            $user = $this->userRepository->getById($userId->getValue());
            $flight = $this->flightRepository->getById($flightId->getValue());
            $place = $this->placeRepository->getByNumberAndFlightId(
                $flight->getId(),
                $reservationData->getPlaceNumber()
            );

        } catch (EntityNotFoundException $exception) {
            throw new ApiNotFoundException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        try {
            $reservation = $this->reservationService->placeReservation($user, $place);
        } catch (EntityExistsException $exception) {
            throw new ApiBadRequestException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => $reservation->toArray(),
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("user/{user_id}/flight/{flight_id}/place/reservation/{reservation_id}", methods={"DELETE"})
     *
     * @param UserId          $userId
     * @param FlightId        $flightId
     * @param ReservationData $reservationData
     * @param ReservationId   $reservationId
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiNotFoundException
     */
    public function cancelReservation(
        UserId $userId,
        FlightId $flightId,
        ReservationData $reservationData,
        ReservationId $reservationId
    ): JsonResponse {
        try {
            $user = $this->userRepository->getById($userId->getValue());
            $flight = $this->flightRepository->getById($flightId->getValue());
            $place = $this->placeRepository->getByNumberAndFlightId(
                $flight->getId(),
                $reservationData->getPlaceNumber()
            );
            $reservation = $this->reservationRepository->getById($reservationId->getValue());
        } catch (EntityNotFoundException $exception) {
            throw new ApiNotFoundException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        $this->isReservationBelongToUser($reservation, $user->getId());

        $this->reservationService->cancelReservation($user, $place, $reservation);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param Reservation $reservation
     * @param int         $userId
     *
     * @return bool
     *
     * @throws NonUniqueResultException
     */
    private function isReservationBelongToUser(Reservation $reservation, int $userId): bool
    {
        try {
            $this->reservationRepository->getByReservationIdAndUserId($reservation->getId(), $userId);
        } catch (EntityNotFoundException $exception) {
            throw new ApiNotFoundException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }
        return true;
    }
}
