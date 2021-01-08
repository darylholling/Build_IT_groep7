<?php

namespace App\MessageHandler;

use App\Entity\Consumption;
use App\Message\NotifyContactsMessage;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

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

        //TODO fix sendmail
//        $consumption->setContactsNotified(true);
//        $this->entityManager->flush();

//        dd($consumption->getUser());
//        return;

//        foreach ($consumption->getUser()->getContacts() as $contact) {
//            $email = (new TemplatedEmail())
//                ->from('noreply@buildit.com')
//                ->addTo(new Address($contact->getEmail(), $contact->getSalutation()))
//                ->subject('Medicatie niet ingenomen')
//                ->htmlTemplate('email/notify_contact.html.twig')
//                ->context([
//                    'contact' => $contact
//                ]);
//
//            $this->mailer->send($email);
//        }
    }
}