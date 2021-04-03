<?php

namespace App\Controller;

use App\DTO\CheckUserData;
use App\DTO\RegisterUserData;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\ApiHttpException\ApiNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;

interface UserControllerInterface
{
    /**
     * @OA\Schema(
     *     type="object",
     *     schema="responseData",
     *     @OA\Property(property="data", ref="#/components/schemas/response"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="response",
     *     @OA\Property(property="id", type="integer", example="1", description="ID юзера"),
     *     @OA\Property(property="email", type="string", example="email@email.com", description="Электронная почта юзера"),
     *     @OA\Property(property="passport", type="object", ref="#/components/schemas/userPassportData"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="userPassportData",
     *     @OA\Property(type="string", property="series", example="2013", description="Серия паспорта"),
     *     @OA\Property(type="string", property="number", example="313131", description="Номер паспорта"),
     *     @OA\Property(type="string", property="division_name", example="313131", description="Название подразделения, выдавшего паспорт"),
     *     @OA\Property(type="string", property="division_code", example="360001", description="Код подразделения, выдавшего паспорт"),
     *     @OA\Property(type="string", property="first_name", example="Иван", description="Имя юзера"),
     *     @OA\Property(type="string", property="last_name", example="Иванов", description="Фамилия юзера"),
     *     @OA\Property(type="string", property="middle_name", example="Иванович", description="Отчество юзера"),
     * )
     * @OA\Get(
     *     path="/user",
     *     summary="Найти юзера",
     *     tags={"/user-api/v1"},
     *     description="Получить данные юзера по паспортным данным",
     *     @OA\Parameter(
     *         name="passport_series",
     *         in="query",
     *         description="Серия паспорта",
     *         required=true,
     *         @OA\Schema(type="string", example="2019")
     *      ),
     *     @OA\Parameter(
     *         name="passport_number",
     *         in="query",
     *         description="Номер паспорта",
     *         required=true,
     *         @OA\Schema(type="string", example="313131")
     *      ),
     *     @OA\Parameter(
     *         name="passport_division_name",
     *         in="query",
     *         description="Название подразделения, выдавшего паспорт",
     *         required=true,
     *         @OA\Schema(type="string", example="ГУ МВД России по Воронежской области")
     *      ),
     *     @OA\Parameter(
     *         name="passport_division_code",
     *         in="query",
     *         description="Код подразделения, выдавшего паспорт",
     *         required=true,
     *         @OA\Schema(type="string", example="360001")
     *      ),
     *     @OA\Parameter(
     *         name="passport_issue_date",
     *         in="query",
     *         description="Дата выдачи",
     *         required=true,
     *         @OA\Schema(type="string", example="30.01.2020")
     *      ),
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="Имя юзера по паспорту",
     *         required=false,
     *         @OA\Schema(type="string", example="Иван")
     *      ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Фамилия юзера по паспорту",
     *         required=false,
     *         @OA\Schema(type="string", example="Иванов")
     *      ),
     *     @OA\Parameter(
     *         name="middle_name",
     *         in="query",
     *         description="Отчество юзера по паспорту",
     *         required=false,
     *         @OA\Schema(type="string", example="Иванович")
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/responseData")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request (Ошибка валидации)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error (Внутренняя ошибка сервера)",
     *         @OA\JsonContent(ref="#/components/schemas/error")
     *     )
     * )
     *
     * @param CheckUserData $checkUserData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiNotFoundException
     */
    public function showUser(CheckUserData $checkUserData): JsonResponse;

    /**
     * @OA\Schema(
     *     type="object",
     *     schema="userIdData",
     *     @OA\Property(type="object", property="data", ref="#/components/schemas/userId"),
     * )
     * @OA\Schema(
     *     type="object",
     *     schema="userId",
     *     @OA\Property(type="integer", property="id", example=1, description="Уникальный номер Юзера"),
     * )
     * @OA\Post(
     *     path="/user",
     *     summary="Запрос на регистрацию пользователя.",
     *     tags={"/user-api/v1"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="email@email.com", description="Электронная почта юзера"),
     *                 @OA\Property(property="passport", type="object", ref="#/components/schemas/userPassportData"),
     *             )
     *         ),
     *         required=true,
     *         description="Пользователь авторизован"
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="CREATED",
     *         @OA\JsonContent(ref="#/components/schemas/userIdData")
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
     * @param RegisterUserData $registerUserData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     */
    public function registerUser(RegisterUserData $registerUserData): JsonResponse;
}