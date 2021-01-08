<?php

namespace App\MessageHandler;

use App\Entity\Consumption;
use App\Manager\ConsumptionManager;
use App\Message\ConsumptionNotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ConsumptionNotificationHandler
 */
class ConsumptionNotificationHandler extends AbstractMessageHandler
{
    /**
     * @var ConsumptionManager
     */
    private ConsumptionManager $consumptionManager;

    /**
     * ConsumptionNotificationHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ConsumptionManager $consumptionManager
     */
    public function __construct(EntityManagerInterface $entityManager, ConsumptionManager $consumptionManager)
    {
        parent::__construct($entityManager);

        $this->consumptionManager = $consumptionManager;
    }

    /**
     * @param ConsumptionNotificationMessage $consumptionNotificationMessage
     * @throws TransportExceptionInterface
     */
    public function __invoke(ConsumptionNotificationMessage $consumptionNotificationMessage): void
    {
        /** @var Consumption $consumption */
        $consumption = $this->entityManager->getRepository(Consumption::class)->find($consumptionNotificationMessage->getConsumptionId());

        if ($consumption === null) {
            throw new RuntimeException(sprintf('Consumption with id %s can not be found', $consumptionNotificationMessage->getConsumptionId()));
        }

        $this->consumptionManager->sendArduinoRequest($consumption);
    }
}