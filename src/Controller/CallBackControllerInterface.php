<?php

namespace App\Controller;

use App\DTO\CallBackData;
use Symfony\Component\HttpFoundation\Response;

interface CallBackControllerInterface
{
    /**
     * @OA\Schema(
     *    type="object",
     *     schema="callbackResponse",
     *    @OA\Property(example="Событие успешно принято и обработано"),
     * )
     *
     * @OA\Post(
     *     path="/event",
     *     summary="Запрос для callback. Принимает два события: кончились билеты и отмена рейса. В случае отмены рейса отправляет письмо на почту пользователям, забронировавшим или купившим место",
     *     tags={"/api/v1/callback"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="flight_id", type="string", example="1", description="Индивидуальный номер рейса"),
     *                 @OA\Property(property="triggered_at", type="string", example="1585012345"),
     *                 @OA\Property(property="event", type="string", example="flight_canceled, flight_ticket_sales_completed", description="Тип события"),
     *                 @OA\Property(property="secret_key", type="string", example="a1b2c3d4e5f6a1b2c3d4e5f6", description="Секретный ключ"),
     *             )
     *         ),
     *         required=true,
     *         description="Событие успешно принято и обрабтано"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED",
     *         @OA\JsonContent(ref="#/components/schemas/callbackResponse")
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
     * @param CallBackData $callBackData
     *
     * @return Response
     */
    public function callback(CallBackData $callBackData): Response;
}
