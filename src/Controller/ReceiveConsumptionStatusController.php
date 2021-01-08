<?php

namespace App\Controller;

use App\Entity\Consumption;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consumptie-status-updaten")
 * Class ReceiveConsumptionStatusController
 */
class ReceiveConsumptionStatusController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function updateStatus(Request $request): Response
    {
        if ($request->query->has('consumption')) {
            /** @var Consumption $consumption */
            $consumption = $this->getDoctrine()->getRepository(Consumption::class)->find($request->query->get('consumption'));

            $consumption->setTaken(true);

            $this->getDoctrine()->getManager()->flush();

            return new Response('success', Response::HTTP_OK);
        }

        return new Response('error', Response::HTTP_BAD_REQUEST);
    }
}