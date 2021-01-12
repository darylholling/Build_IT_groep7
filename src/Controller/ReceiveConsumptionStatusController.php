<?php

namespace App\Controller;

use App\Entity\Consumption;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 * Class ReceiveConsumptionStatusController
 */
class ReceiveConsumptionStatusController extends AbstractController
{
    /**
     * @Route("/consumptie-status-updaten", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function updateStatus(Request $request): Response
    {
        $consumptionId = null;

        if ($request->getMethod() === Request::METHOD_GET) {
            if ($request->query->has('consumption') === false) {
                return new Response('The request does not contain query key consumption', Response::HTTP_BAD_REQUEST);
            }

            $consumptionId = $request->query->get('consumption');
        }

        if ($request->getMethod() === Request::METHOD_POST) {
            if ($request->request->has('consumption') === false) {
                return new Response('The request does not contain request param key consumption', Response::HTTP_BAD_REQUEST);
            }

            $consumptionId = (int)$request->request->get('consumption');
        }

        if ($consumptionId === null) {
            return new Response(sprintf('Request method %s is not allowed', $request->getMethod()), Response::HTTP_BAD_REQUEST);
        }

        /** @var Consumption $consumption */
        $consumption = $this->getDoctrine()->getRepository(Consumption::class)->find($consumptionId);

        if ($consumption === null) {
            return new Response(sprintf('No consumption found for id %s', $consumptionId), Response::HTTP_NO_CONTENT);
        }

        $consumption->setTaken(true);

        $this->getDoctrine()->getManager()->flush();

        return new Response(sprintf('Status successfully updated for consumption using id %s', $consumptionId), Response::HTTP_OK);
    }
}