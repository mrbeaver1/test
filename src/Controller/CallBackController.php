<?php

namespace App\Controller;

use App\DTO\CallBackData;
use App\Exception\ApiHttpException\ApiNotFoundException;
use App\Repository\FlightRepositoryInterface;
use App\Services\MailerService;
use App\VO\ApiErrorCode;
use App\VO\Event;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/callback")
 */
class CallBackController extends BaseApiController implements CallBackControllerInterface
{
    /**
     * @var FlightRepositoryInterface
     */
    private $flightRepository;

    /**
     * @var MailerService
     */
    private $mailerService;

    /**
     * @param FlightRepositoryInterface $flightRepository
     * @param MailerService             $mailerService
     */
    public function __construct(FlightRepositoryInterface $flightRepository, MailerService $mailerService)
    {
        $this->flightRepository = $flightRepository;
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/event", methods={"POST"})
     *
     * @param CallBackData $callBackData
     *
     * @return Response
     */
    public function callback(CallBackData $callBackData): Response
    {
        if ($callBackData->getEvent() == Event::FLIGHT_CANCELED) {
            try {
                $flight = $this->flightRepository->getById($callBackData->getFlightId());
            } catch (EntityNotFoundException $exception) {
                throw new ApiNotFoundException(
                    [$exception->getMessage()],
                    new ApiErrorCode(ApiErrorCode::ENTITY_NOT_FOUND)
                );
            }

            $places = $flight->getPlaces()->toArray();

            foreach ($places as $place) {
                $ticket = $place->getTicket();

                if (empty($ticket)) {
                    $reservation = $place->getReservation();

                    if (empty($reservation)) {
                        continue;
                    }

                    $reservationOwner = $reservation->getOwner();

                    $this->mailerService->sendEmail(
                        '???????????????????? ???????? ????????????????',
                        '?? ?????????????????? ?????? ???????? ??????????????',
                        [$reservationOwner->getEmail()->getValue()]
                    );
                } else {
                    $ticketOwner = $ticket->getOwner();

                    $this->mailerService->sendEmail(
                        '???????????????????? ???????? ????????????????',
                        '?? ?????????????????? ?????? ???????? ??????????????',
                        $ticketOwner->getEmail()
                    );
                }
            }
        }

        return new Response('?????????????? ?????????????? ?????????????? ?? ????????????????????');
    }
}
