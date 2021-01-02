<?php

namespace App\Message;

/**
 * Class NotifyContactsMessage
 */
class NotifyContactsMessage
{
    /**
     * @var int
     */
    private $consumptionId;

    /**
     * NotifyContactsMessage constructor.
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