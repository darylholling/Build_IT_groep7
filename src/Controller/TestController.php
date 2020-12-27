<?php

namespace App\Controller;

use App\Entity\ConsumptionMoment;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        $consumptionsMoments = $this->getDoctrine()->getRepository(ConsumptionMoment::class)->findBy([
            'user' => $this->getUser()
        ]);

        return [
            'consumptionMoments' => $consumptionsMoments
        ];
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