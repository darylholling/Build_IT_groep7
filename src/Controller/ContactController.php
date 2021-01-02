<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @Route("/contacten")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findBy([
            'user' => $this->getUser()
        ]);

        return [
            'contacts' => $contacts
        ];
    }

    /**
     * @Route("/nieuw", methods={"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function new(Request $request)
    {
        $contact = new Contact();
        $contact->setUser($this->getUser());

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->persist($contact);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_contact_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contact}/bewerken", methods={"GET", "POST"})
     * @Template()
     * @param Contact $contact
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function edit(Contact $contact, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $contact);

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_contact_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{contact}/verwijderen", methods={"GET", "DELETE"})
     * @param Contact $contact
     */
    public function delete(Contact $contact)
    {
        $this->denyAccessUnlessGranted('delete', $contact);

    }
}