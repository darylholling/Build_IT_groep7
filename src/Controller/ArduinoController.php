<?php

namespace App\Controller;

use App\Entity\Arduino;
use App\Entity\User;
use App\Form\ArduinoType;
use Knp\Component\Pager\PaginatorInterface;
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
        /** @var User $user */
        $user = $this->getUser();

        return [
            'arduino' => $user->getActiveArduino()
        ];
    }

    /**
     * @Route("/inactief", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function listInactive(Request $request, PaginatorInterface $paginator): array
    {
        /** @var User $user */
        $user = $this->getUser();

        $paginator = $paginator->paginate(
            $user->getInactiveArduinos(),
            $request->query->getInt('page', 1)
        );

        return [
            'arduinos' => $paginator
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

        /** @var User $user */
        $user = $this->getUser();
        $user->addArduino($arduino);

        $this->denyAccessUnlessGranted('new', $arduino);

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
     * @Route("/{arduino}/activeren", methods={"GET", "DELETE"})
     * @param Arduino $arduino
     * @return RedirectResponse
     */
    public function activate(Arduino $arduino): RedirectResponse
    {
        $this->denyAccessUnlessGranted('activate', $arduino);

        $arduino->setActive(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_arduino_index');
    }

    /**
     * @Route("/{arduino}/deactiveren", methods={"GET", "DELETE"})
     * @param Arduino $arduino
     * @return RedirectResponse
     */
    public function deactivate(Arduino $arduino): RedirectResponse
    {
        $this->denyAccessUnlessGranted('deactivate', $arduino);

        $arduino->setActive(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_arduino_index');
    }
}