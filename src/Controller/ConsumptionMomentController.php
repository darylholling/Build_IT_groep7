<?php

namespace App\Controller;

use App\Entity\ConsumptionMoment;
use App\Form\ConsumptionMomentType;
use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConsumptionMomentController
 * @Route("/consumptie-momenten")
 */
class ConsumptionMomentController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        //TODO fix paginator
        $consumptionMoments = $this->getDoctrine()->getRepository(ConsumptionMoment::class)->findBy([
            'user' => $this->getUser()
        ]);

        return [
            'consumptionMoments' => $consumptionMoments
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
        $consumptionMoment = new ConsumptionMoment();

        $this->denyAccessUnlessGranted('new', $consumptionMoment);
        $consumptionMoment->setUser($this->getUser());

        $form = $this->createForm(ConsumptionMomentType::class, $consumptionMoment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($consumptionMoment);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_consumptionmoment_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{consumptionMoment}/bewerken", methods={"GET", "POST"})
     * @Template()
     * @param ConsumptionMoment $consumptionMoment
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function edit(ConsumptionMoment $consumptionMoment, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $consumptionMoment);

        $form = $this->createForm(ConsumptionMomentType::class, $consumptionMoment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_consumptionmoment_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{consumptionMoment}/verwijderen", methods={"GET", "DELETE"})
     * @param ConsumptionMoment $consumptionMoment
     * @return RedirectResponse
     */
    public function delete(ConsumptionMoment $consumptionMoment): RedirectResponse
    {
        $this->denyAccessUnlessGranted('delete', $consumptionMoment);

        $this->getDoctrine()->getManager()->remove($consumptionMoment);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_contact_index');
    }
}