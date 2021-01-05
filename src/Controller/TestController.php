<?php

namespace App\Controller;

use App\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     * @param MailerInterface $mailer
     * @throws TransportExceptionInterface
     */
    public function index(MailerInterface $mailer)
    {

        $contact = $this->getDoctrine()->getRepository(Contact::class)->findAll()[0];

        $email = (new TemplatedEmail())
            ->from('noreply@buildit.com')
            ->addTo(new Address('daryl_holling@hotmail.com', 'Daryl'))
            ->subject('Medicatie niet ingenomen')
            ->htmlTemplate('email/notify_contact.html.twig')
            ->context([
                'contact' => $contact
            ]);

        $mailer->send($email);

        return new Response(200, 'ok');
//        return [
//        ];
    }

    /**
     * @Route("/bekijken", methods={"GET"})
     * @Template()
     */
    public function view(): array
    {
        return [

        ];
    }
}