<?php

namespace App\EventListener;

use App\Entity\Consumption;
use App\Helper\DelayStampHelper;
use App\Message\ConsumptionNotificationMessage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CreateConsumptionNotificationListener
 */
class CreateConsumptionNotificationListener
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CreateConsumptionNotificationListener constructor.
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $entityManager)
    {
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Consumption $consumption
     * @param LifecycleEventArgs $event
     */
    public function postPersist(Consumption $consumption, LifecycleEventArgs $event)
    {
        $this->messageBus->dispatch(new Envelope(
            new ConsumptionNotificationMessage($consumption->getId()), [
                (new DelayStampHelper)($consumption->getDateTime())
            ]
        ));
    }
}