<?php


namespace App\Controller;


use App\DTO\CreateFlightData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;

interface FlightControllerInterface
{
    /**
     * @OA\Schema(
     *     type="object",
     *     schema="flightIdData",
     *     @OA\Property(type="object", property="data", ref="#/components/schemas/flightId"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="flightId",
     *     @OA\Property(type="integer", property="id", example=1, description="Уникальный номер рейса"),
     * )
     *
     * @OA\Post(
     *     path="/flight",
     *     summary="Запрос на создание рейса.",
     *     tags={"/api-flight/v1"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="number", type="integer", example=1, description="Номер рейса"),
     *                 @OA\Property(property="date", type="string", example="20.12.2020", description="Дата полета"),
     *                 @OA\Property(property="place_count", type="integer", example=150, description="Количество мест в самолете(150)"),
     *             )
     *         ),
     *         required=true,
     *         description="Рейс создан"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED",
     *         @OA\JsonContent(ref="#/components/schemas/flightIdData")
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
     * @param CreateFlightData $createFlightData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     */
    public function createFlight(CreateFlightData $createFlightData): JsonResponse;
}