<?php

namespace App\Manager;

use App\Entity\Consumption;
use App\Entity\User;
use App\Message\NotifyContactsMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ConsumptionManager
 */
class ConsumptionManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * ConsumptionManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param HttpClientInterface $httpClient
     * @param MessageBusInterface $messageBus
     */
    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->messageBus = $messageBus;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function createConsumptions(): void
    {
        $qb = $this->entityManager->getRepository(User::class)->createQueryBuilder('user');
        $qb->join('user.consumptionMoments', 'consumptionMoment');
        $qb->join('user.arduino', 'arduino');
        $qb->andWhere("consumptionMoment.active = '1'");

        $results = $qb->getQuery()->getResult();

        foreach ($results as $user) {
            foreach ($user->getConsumptionMoments() as $consumptionMoment) {
                $consumption = new Consumption();
                $consumption->setUser($user);
                $consumption->setDateTime(new DateTime($consumptionMoment->getDateTime()->format('H:i')));

                $this->entityManager->persist($consumption);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param Consumption $consumption
     * @throws TransportExceptionInterface
     */
    public function sendArduinoRequest(Consumption $consumption): void
    {
        $response = $this->httpClient->request(
            'GET',
            $consumption->getUser()->getActiveArduino()->getUrl()
        );

        //TODO check if below is error proof
        if ($response->getStatusCode() === Response::HTTP_OK) {
//            $consumption->setArduinoNotified(true);

//            $this->messageBus->dispatch(new NotifyContactsMessage($consumption->getId()));
//            $this->messageBus->dispatch(new Envelope(
//                new NotifyContactsMessage($consumption->getId()), [
//                    (new DelayStampHelper)(new DateTime('+15 minute'))
//                ]
//            ));
//            $consumption->setResponseStatusCode($response->getStatusCode());
        }

        $this->entityManager->flush();
    }
}