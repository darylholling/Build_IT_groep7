<?php

namespace App\EventListener;

use App\Entity\Consumption;
use App\Entity\ConsumptionMoment;
use App\Helper\DelayStampHelper;
use App\Message\ConsumptionNotificationMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CreateConsumptionMomentListener
 */
class CreateConsumptionMomentListener
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
     * @param ConsumptionMoment $consumptionMoment
     * @param LifecycleEventArgs $event
     * @throws Exception
     */
    public function postPersist(ConsumptionMoment $consumptionMoment, LifecycleEventArgs $event)
    {
        return;
        $now = new DateTime();

        //TODO fix this
        if ($consumptionMoment->getDateTime()->format('H:i') >= $now->format('H:i')) {
            die();
            $consumption = new Consumption();
            $consumption->setUser($consumptionMoment->getUser());
            $consumption->setDateTime(new DateTime($consumptionMoment->getDateTime()->format('H:i')));

            $this->entityManager->persist($consumption);
        }

        die();
    }
}