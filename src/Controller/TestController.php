<?php

namespace App\Controller;

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
        return [

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