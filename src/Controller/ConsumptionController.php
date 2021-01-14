<?php

namespace App\Controller;

use App\Entity\Consumption;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function index(Request $request, PaginatorInterface $paginator): array
    {
        return $this->showTemplate($request, $paginator);
    }

    /**
     * @Route("/ingenomen-consumpties", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function taken(Request $request, PaginatorInterface $paginator): array
    {
        return $this->showTemplate($request, $paginator, 1);
    }
    /**
     * @Route("/vergeten-consumpties", methods={"GET"})
     * @Template()
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return array
     */
    public function forgotten(Request $request, PaginatorInterface $paginator): array
    {
        return $this->showTemplate($request, $paginator, 0);
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

    private function showTemplate(Request $request, PaginatorInterface $paginator, bool $taken = null): array
    {
        $findByParams = [
            'user' => $this->getUser()
        ];

        if(null !== $taken) {
            $findByParams['taken'] = $taken;
        }

        $consumptions = $this->getDoctrine()->getRepository(Consumption::class)->findBy($findByParams, [
            'dateTime' => 'DESC'
        ]);

        $consumptions = $paginator->paginate(
            $consumptions,
            $request->query->getInt('page', 1),
            5
        );
        return [
            'consumptions' => $consumptions,
        ];
    }
}