<?php

namespace App\Controller;

use App\DTO\PlaceNumberData;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Exception\EntityException\EntityExistsException;
use App\Repository\FlightRepositoryInterface;
use App\Repository\PlaceRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\TicketRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Services\ReservationService;
use App\Services\TicketService;
use App\VO\ApiErrorCode;
use App\VO\FlightId;
use App\VO\ReservationId;
use App\VO\TicketId;
use App\VO\UserId;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
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
     * @var TicketService
     */
    private $ticketService;

    /**
     * @var TicketRepositoryInterface
     */
    private $ticketRepository;

    /**
     * @param UserRepositoryInterface        $userRepository
     * @param FlightRepositoryInterface      $flightRepository
     * @param PlaceRepositoryInterface       $placeRepository
     * @param ReservationService             $reservationService
     * @param ReservationRepositoryInterface $reservationRepository
     * @param TicketService                  $ticketService
     * @param TicketRepositoryInterface      $ticketRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        FlightRepositoryInterface $flightRepository,
        PlaceRepositoryInterface $placeRepository,
        ReservationService $reservationService,
        ReservationRepositoryInterface $reservationRepository,
        TicketService $ticketService,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->userRepository = $userRepository;
        $this->flightRepository = $flightRepository;
        $this->placeRepository = $placeRepository;
        $this->reservationService = $reservationService;
        $this->reservationRepository = $reservationRepository;
        $this->ticketService = $ticketService;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @Route("/user/{user_id}/flight/{flight_id}/place/reservation", methods={"POST"})
     *
     * @param UserId          $userId
     * @param FlightId        $flightId
     * @param PlaceNumberData $reservationData
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
        PlaceNumberData $reservationData
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
     * @Route("/user/{user_id}/flight/{flight_id}/place/reservation/{reservation_id}", methods={"DELETE"})
     *
     * @param UserId          $userId
     * @param FlightId        $flightId
     * @param PlaceNumberData $reservationData
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
        PlaceNumberData $reservationData,
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
     * @Route("/user/{user_id}/flight/{flight_id}/place/ticket/pay", methods={"POST"})
     *
     * @param UserId $userId
     * @param FlightId $flightId
     * @param PlaceNumberData $reservationData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     * @throws ApiNotFoundException
     */
    public function payTicket(
        UserId $userId,
        FlightId $flightId,
        PlaceNumberData $reservationData
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
            $ticket = $this->ticketService->createTicket($user, $flight, $place);
        } catch (EntityExistsException $exception) {
            throw new ApiBadRequestException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => $ticket->toArray(),
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/user/{user_id}/flight/{flight_id}/place/ticket/{ticket_id}/return", methods={"DELETE"})
     *
     * @param UserId          $userId
     * @param FlightId        $flightId
     * @param PlaceNumberData $reservationData
     * @param TicketId        $ticketId
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiNotFoundException
     */
    public function returnTicket(
        UserId $userId,
        FlightId $flightId,
        PlaceNumberData $reservationData,
        TicketId $ticketId
    ): JsonResponse {
        try {
            $user = $this->userRepository->getById($userId->getValue());
            $flight = $this->flightRepository->getById($flightId->getValue());
            $place = $this->placeRepository->getByNumberAndFlightId(
                $flight->getId(),
                $reservationData->getPlaceNumber()
            );
            $ticket = $this->ticketRepository->getById($ticketId->getValue());
        } catch (EntityNotFoundException $exception) {
            throw new ApiNotFoundException(
                [$exception->getMessage()],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        $this->isTicketBelongToUser($ticket, $user->getId());

        $this->ticketService->returnTicket($user, $flight, $place, $ticket);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param Reservation $reservation
     * @param int         $userId
     *
     * @return bool
     *
     * @throws ApiNotFoundException
     */
    private function isReservationBelongToUser(Reservation $reservation, int $userId): bool
    {
        if ($reservation->getOwner()->getId() !== $userId) {
            throw new ApiNotFoundException(
                ['Бронь не найдена'],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        return true;
    }

    /**
     * @param Ticket $ticket
     * @param int    $userId
     *
     * @return bool
     *
     * @throws ApiNotFoundException

     */
    private function isTicketBelongToUser(Ticket $ticket, int $userId): bool
    {
        if ($ticket->getOwner()->getId() !== $userId) {
            throw new ApiNotFoundException(
                ['Билет не найден'],
                new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
            );
        }

        return true;
    }
}
