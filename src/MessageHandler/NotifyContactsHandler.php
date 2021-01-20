<?php

namespace App\MessageHandler;

use App\Entity\Consumption;
use App\Entity\User;
use App\Message\NotifyContactsMessage;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Class NotifyContactsHandler
 */
class NotifyContactsHandler extends AbstractMessageHandler
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * NotifyContactsHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     */
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        parent::__construct($entityManager);

        $this->mailer = $mailer;
    }

    /**
     * @param NotifyContactsMessage $notifyContactsMessage
     * @throws TransportExceptionInterface
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

        /** @var User $user */
        $user = $consumption->getUser();

        if ($user->getContacts()->isEmpty()) {
            return;
        }

        $emails = [];

        foreach ($user->getContacts() as $contact) {
            $email = (new TemplatedEmail())
                ->from('noreply@darylholling.nl')
                ->addTo(new Address($contact->getEmail(), $contact->getSalutation()))
                ->subject('Medicatie niet ingenomen')
                ->htmlTemplate('email/notify_contact.html.twig')
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

        $consumption->setContactsNotified(true);
    }
}