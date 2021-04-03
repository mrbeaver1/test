<?php

namespace App\VO;

use InvalidArgumentException;

class Event
{
    /**
     * Все билеты были распроданы
     */
    public const FLIGHT_TICKET_SALES_COMPLETED = 'flight_ticket_sales_completed';

    /**
     * Рейс отменен
     */
    public const FLIGHT_CANCELED = 'flight_canceled';

    /**
     * Допустимые события
     */
    public const VALID_EVENTS = [
        self::FLIGHT_TICKET_SALES_COMPLETED,
        self::FLIGHT_CANCELED
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_EVENTS)) {
            throw new InvalidArgumentException("Недопустимое событие: $value");
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
