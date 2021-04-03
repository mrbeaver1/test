<?php

namespace App\DTO;

use App\VO\Event;

class CallBackData
{
    /**
     * @var int
     */
    private $flightId;

    /**
     * @var string
     */
    private $triggeredAt;

    /**
     * @var Event
     */
    private $event;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @param int    $flightId
     * @param string $triggeredAt
     * @param Event  $event
     * @param string $secretKey
     */
    public function __construct(
        int $flightId,
        string $triggeredAt,
        Event $event,
        string $secretKey
    ) {
        $this->flightId = $flightId;
        $this->triggeredAt = $triggeredAt;
        $this->event = $event;
        $this->secretKey = $secretKey;
    }

    /**
     * @return int
     */
    public function getFlightId(): int
    {
        return $this->flightId;
    }

    /**
     * @return string
     */
    public function getTriggeredAt(): string
    {
        return $this->triggeredAt;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
