<?php

namespace App\Controller;

use App\Entity\Consumption;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'consumptions' => $consumptions
        ];
    }
}