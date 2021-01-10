<?php

namespace App\Controller;

use App\Entity\Consumption;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConsumptionController
 * @Route("/consumpties")
 */
class ConsumptionController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        $consumptions = $this->getDoctrine()->getRepository(Consumption::class)->findBy([
            'user' => $this->getUser()
        ], [
            'dateTime' => 'DESC'
        ]);

        return [
            'consumptions' => $consumptions,
        ];
    }

    /**
     * @Route("/{consumption}/verwijderen", methods={"GET", "DELETE"})
     * @param Consumption $consumption
     * @return RedirectResponse
     */
    public function delete(Consumption $consumption): RedirectResponse
    {
        $this->denyAccessUnlessGranted('delete', $consumption);

        $this->getDoctrine()->getManager()->remove($consumption);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_consumption_index');
    }
}