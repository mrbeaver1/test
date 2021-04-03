<?php


namespace App\Controller;


use App\DTO\PlaceNumberData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\ApiHttpException\ApiNotFoundException;
use App\VO\FlightId;
use App\VO\ReservationId;
use App\VO\TicketId;
use App\VO\UserId;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiControllerInterface
{
    /**
     * @OA\Schema(
     *     type="object",
     *     schema="reservationResponseData",
     *     @OA\Property(type="object", property="data", ref="#/components/schemas/reservationResponse"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="reservationResponse",
     *     @OA\Property(type="integer", property="id", example=1, description="Уникальный номер брони"),
     *     @OA\Property(type="integer", property="owner", example=1, description="Уникальный номер владельца брони"),
     *     @OA\Property(type="integer", property="place", example=1, description="Уникальный номер места в самолете"),
     *     @OA\Property(type="string", property="created_at", example="2005-08-15T15:52:01+00:00", description="Дата и время создания"),
     * )
     *
     * @OA\Post(
     *     path="/user/{user_id}/flight/{flight_id}/place/reservation",
     *     summary="Запрос на бронирование места.",
     *     tags={"/api/v1"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID юзера",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *      @OA\Parameter(
     *         name="flight_id",
     *         in="path",
     *         description="ID рейса",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="place_number", type="integer", example=1, description="Номер места для бронирования"),
     *             )
     *         ),
     *         required=true,
     *         description="Место зарезервировано"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED",
     *         @OA\JsonContent(ref="#/components/schemas/reservationResponseData")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request (Ошибка валидации)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found (Не найдено)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error (Внутренняя ошибка сервера)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     )
     * )
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
    ): JsonResponse;

    /**
     * @OA\Delete (
     *     path="/user/{user_id}/flight/{flight_id}/place/reservation/{reservation_id}",
     *     summary="Отменить бронь",
     *     tags={"/api/v1"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID юзера",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *      @OA\Parameter(
     *         name="flight_id",
     *         in="path",
     *         description="ID рейса",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="reservation_id",
     *         in="path",
     *         description="ID брони",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="NO_CONTENT"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request (Ошибка валидации)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found (Не найдено)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error (Внутренняя ошибка сервера)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     )
     * )
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
    ): JsonResponse;

    /**
     * @OA\Schema(
     *     type="object",
     *     schema="ticetResponseData",
     *     @OA\Property(type="object", property="data", ref="#/components/schemas/ticetResponse"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="ticetResponse",
     *     @OA\Property(type="integer", property="id", example=1, description="Уникальный номер брони"),
     *     @OA\Property(type="integer", property="owner", example=1, description="Уникальный номер владельца брони"),
     *     @OA\Property(type="integer", property="place", example=1, description="Уникальный номер места в самолете"),
     *     @OA\Property(type="integer", property="flight", example="1", description="Уникальный номер рейса"),
     * )
     * @OA\Post(
     *     path="/user/{user_id}/flight/{flight_id}/place/ticket/pay",
     *     summary="Запрос на покупку билета.",
     *     tags={"/api/v1"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID юзера",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *      @OA\Parameter(
     *         name="flight_id",
     *         in="path",
     *         description="ID рейса",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="place_number", type="integer", example=1, description="Номер места для бронирования"),
     *             )
     *         ),
     *         required=true,
     *         description="Билет куплен"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED",
     *         @OA\JsonContent(ref="#/components/schemas/ticetResponseData")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request (Ошибка валидации)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found (Не найдено)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error (Внутренняя ошибка сервера)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     )
     * )
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
    ): JsonResponse;

    /**
     * @OA\Delete (
     *     path="/user/{user_id}/flight/{flight_id}/place/ticket/{ticket_id}/return",
     *     summary="Вернуть билет",
     *     tags={"/api/v1"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="ID юзера",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *      @OA\Parameter(
     *         name="flight_id",
     *         in="path",
     *         description="ID рейса",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Parameter(
     *         name="ticket_id",
     *         in="path",
     *         description="ID билета",
     *         required=true,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="NO_CONTENT"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request (Ошибка валидации)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found (Не найдено)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error (Внутренняя ошибка сервера)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     )
     * )
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
    ): JsonResponse;
}