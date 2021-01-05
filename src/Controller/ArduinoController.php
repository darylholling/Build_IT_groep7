<?php

namespace App\Controller;

use App\Entity\Arduino;
use App\Form\ArduinoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConsumptionMomentController
 * @Route("/mijn-arduino")
 */
class ArduinoController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        return [
            'arduino' => $this->getUser()->getArduino()
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
        $arduino = new Arduino();
        $this->getUser()->setArduino($arduino);

        $form = $this->createForm(ArduinoType::class, $arduino);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($arduino);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_arduino_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{arduino}/bewerken", methods={"GET", "POST"})
     * @Template()
     * @param Arduino $arduino
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function edit(Arduino $arduino, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $arduino);

        $form = $this->createForm(ArduinoType::class, $arduino);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_arduino_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{arduino}/verwijderen", methods={"GET", "DELETE"})
     * @param Arduino $arduino
     */
    public function delete(Arduino $arduino)
    {
        $this->denyAccessUnlessGranted('delete', $arduino);

    }
}