<?php

namespace App\Controller;

use App\DTO\CreateFlightData;
use App\Entity\Flight;
use App\Exception\ApiHttpException\ApiBadRequestException;
use App\Exception\EntityException\EntityExistsException;
use App\Repository\FlightRepositoryInterface;
use App\Services\FlightService;
use App\VO\ApiErrorCode;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api-flight/v1")
 */
class FlightController extends BaseApiController implements FlightControllerInterface
{
    /**
     * @var FlightRepositoryInterface
     */
    private $flightRepository;

    /**
     * @var FlightService
     */
    private $flightService;

    /**
     * @param FlightRepositoryInterface $flightRepository
     * @param FlightService             $flightService
     */
    public function __construct(
        FlightRepositoryInterface $flightRepository,
        FlightService $flightService
    ) {
        $this->flightRepository = $flightRepository;
        $this->flightService = $flightService;
    }

    /**
     * @Route("/flight", methods={"POST"})
     *
     * @param CreateFlightData $createFlightData
     *
     * @return JsonResponse
     *
     * @throws NonUniqueResultException
     * @throws ApiBadRequestException
     */
    public function createFlight(CreateFlightData $createFlightData): JsonResponse
    {
        $flight = $this->flightRepository->findByFlightNumber($createFlightData->getNumber());

        if (!is_null($flight)) {
            throw new ApiBadRequestException(
                ["Рейс {$createFlightData->getNumber()} уже существует в системе"],
                new ApiErrorCode(ApiErrorCode::ENTITY_EXISTS)
            );
        }

        return new JsonResponse([
            'data' => [
                'id' => $this->flightService->create(
                    $createFlightData->getNumber(),
                    $createFlightData->getDate(),
                    $createFlightData->getPlacesCount()
                )->getId(),
            ],
        ], JsonResponse::HTTP_CREATED);
    }
}
