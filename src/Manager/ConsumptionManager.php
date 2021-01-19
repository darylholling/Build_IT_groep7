<?php

namespace App\Manager;

use App\Entity\Consumption;
use App\Entity\User;
use App\Helper\DelayStampHelper;
use App\Message\NotifyContactsMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
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
     * @var MailerInterface
     */
    private $mailer;

    /**
     * ConsumptionManager constructor.
     * @param HttpClientInterface $httpClient
     * @param MessageBusInterface $messageBus
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     */
    public function __construct(HttpClientInterface $httpClient, MessageBusInterface $messageBus, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->httpClient = $httpClient;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function createConsumptions(): void
    {
        $qb = $this->entityManager->getRepository(User::class)->createQueryBuilder('user');
        $qb->join('user.consumptionMoments', 'consumptionMoment');
        $qb->join('user.arduinos', 'arduino');
        $qb->andWhere("arduino.active = '1'");
        $qb->andWhere("consumptionMoment.active = '1'");

        $results = $qb->getQuery()->getResult();

        if (empty($results)) {
            return;
        }

        /** @var User $user */
        foreach ($results as $user) {
            foreach ($user->getConsumptionMoments() as $consumptionMoment) {
                $consumption = new Consumption();
                $consumption->setDateTime(new DateTime($consumptionMoment->getDateTime()->format('H:i')));

                $user->addConsumption($consumption);

                $this->entityManager->persist($consumption);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @param OutputInterface|null $output
     * @return void
     */
    public function createSingleConsumption(User $user, OutputInterface $output = null): void
    {
        if ($user->getActiveArduino() === null) {
            if ($output !== null && $output->isVerbose()) {
                $output->writeln(sprintf('No active arduino found for user %s', $user->getId()));
            }

            return;
        }

        $consumption = new Consumption();
        $consumption->setDateTime(new DateTime());

        $user->addConsumption($consumption);

        $this->entityManager->persist($consumption);
        $this->entityManager->flush();

        if ($output !== null && $output->isVerbose()) {
            $output->writeln(sprintf('Consumption with id %s created', $consumption->getId()));
        }
    }

    /**
     * @param Consumption $consumption
     * @throws TransportExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendArduinoRequest(Consumption $consumption): void
    {
        $this->httpClient->request(
            'GET',
            sprintf('%s/?id=%s',
                $consumption->getUser()->getActiveArduino()->getUrl(),
                $consumption->getId())
        );

        $consumption->setArduinoNotified(true);

        $this->handlePostArduinoRequestActions($consumption);

        $this->entityManager->flush();
    }

    /**
     * @param Consumption $consumption
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function sendRefillEmail(Consumption $consumption)
    {
        $user = $consumption->getUser();

        if ($user->getContacts()->isEmpty()) {
            return;
        }

        $emails = [];

        foreach ($user->getContacts() as $contact) {
            $email = (new TemplatedEmail())
                ->from('noreply@darylholling.nl')
                ->addTo(new Address($contact->getEmail(), $contact->getSalutation()))
                ->subject('Medicatiedoos moet bijgevuld worden')
                ->htmlTemplate('email/refill_box.html.twig')
                ->context([
                    'contact' => $contact
                ]);

            $emails[] = $email;
        }

        //TODO line below is important (maybe find replacement)
        if ($_ENV['APP_ENV'] === 'prod') {
            foreach ($emails as $email) {
                $this->mailer->send($email);
            }
        }
    }

    /**
     * @param Consumption $consumption
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function handlePostArduinoRequestActions(Consumption $consumption)
    {
        $user = $consumption->getUser();

        $user->setConsumptionQuantity($user->getConsumptionQuantity() + 1);

        if ($user->getConsumptionQuantity() === 6) {
            $this->sendRefillEmail($consumption);

            $user->setConsumptionQuantity(0);
        }

        $this->messageBus->dispatch(new Envelope(
            new NotifyContactsMessage($consumption->getId()), [
                (new DelayStampHelper)(new DateTime('+15 minute'))
            ]
        ));
    }
}