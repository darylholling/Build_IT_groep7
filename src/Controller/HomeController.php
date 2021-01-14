<?php

namespace App\Controller;

use App\Entity\Consumption;
use DateTime;
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

        $date = date('H');

        if($date >= 5 && $date < 12) {
            $moment = "Goedemorgen";
        } else if ($date >= 12 && $date < 18) {
            $moment = "Goedemiddag";
        } else if ($date >= 18 && $date <= 23 ) {
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