<?php

namespace App\Controller;

use App\DTO\CreateFlightData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api-flight/v1")
 */
class FlightController extends AbstractController
{
    public function createFlight(CreateFlightData $createFlightData): JsonResponse
    {

    }
}
