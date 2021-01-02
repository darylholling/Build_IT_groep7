<?php

namespace App\MessageHandler;

use App\Entity\Consumption;
use App\Message\NotifyContactsMessage;
use RuntimeException;

/**
 * Class NotifyContactsHandler
 */
class NotifyContactsHandler extends AbstractMessageHandler
{
    /**
     * @param NotifyContactsMessage $notifyContactsMessage
     */
    public function __invoke(NotifyContactsMessage $notifyContactsMessage)
    {
        $consumption = $this->entityManager->getRepository(Consumption::class)->find($notifyContactsMessage->getConsumptionId());

        if ($consumption === null) {
            throw new RuntimeException(sprintf('Consumption with id %s can not be found', $notifyContactsMessage->getConsumptionId()));
        }

        if ($consumption->isTaken()) {
            return;
        }

        //TODO sendmail;

    }
}