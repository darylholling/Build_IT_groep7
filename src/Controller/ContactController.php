<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function index(Request $request, PaginatorInterface $paginator): array
    {
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findBy([
            'user' => $this->getUser()
        ]);

        $paginator = $paginator->paginate(
            $contacts,
            $request->query->getInt('page', 1)
        );

        return [
            'contacts' => $paginator
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

        $this->denyAccessUnlessGranted('new', $contact);

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
     * @return RedirectResponse
     */
    public function delete(Contact $contact): RedirectResponse
    {
        $this->denyAccessUnlessGranted('delete', $contact);

        $this->getDoctrine()->getManager()->remove($contact);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_contact_index');
    }
}