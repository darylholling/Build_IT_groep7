<?php

namespace App\Message;

/**
 * Class ConsumptionNotificationMessage
 */
class ConsumptionNotificationMessage
{
    /**
     * @var int
     */
    private $consumptionId;

    /**
     * ConsumptionNotificationMessage constructor.
     * @param int $consumptionId
     */
    public function __construct(int $consumptionId)
    {
        $this->consumptionId = $consumptionId;
    }

    /**
     * @return int
     */
    public function getConsumptionId(): int
    {
        return $this->consumptionId;
    }
}