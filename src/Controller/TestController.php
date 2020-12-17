<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController
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
}