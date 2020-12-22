<?php

namespace App\Controller;

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
        return [

        ];
    }

}