<?php

namespace App\Controller;

use App\Entity\Consumption;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 * Class HomeController
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/")
     * @Template()
     */
    public function index(): array
    {
        $consumptions = $this->getDoctrine()->getRepository(Consumption::class)->findConsumptionsForToday($this->getUser());

        if(date('H') >= 5 && date('H') < 12) {
            $moment = "Goedemorgen";
        } if (date('H') >= 12 && date('H') < 18) {
            $moment = "Goedemiddag";
        } if (date('H') >= 18 && date('H')<= 23 ) {
            $moment = "Goedenavond";
        } else {
            $moment = "Goedenacht";
        }

        return [
            'consumptions' => $consumptions,
            'moment' => $moment
        ];
    }
}