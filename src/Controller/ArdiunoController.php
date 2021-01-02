<?php

namespace App\Controller;

use App\Entity\Ardiuno;
use App\Form\ArdiunoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConsumptionMomentController
 * @Route("/mijn-ardiuno")
 */
class ArdiunoController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @Template()
     */
    public function index(): array
    {
        $ardiuno = $this->getDoctrine()->getRepository(Ardiuno::class)->findOneBy([
            'user' => $this->getUser()
        ]);

        return [
            'ardiuno' => $ardiuno
        ];
    }

    /**
     * @Route("/nieuw", methods={"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function new(Request $request)
    {
        $ardiuno = new Ardiuno();
        $ardiuno->setUser($this->getUser());

        $form = $this->createForm(ArdiunoType::class, $ardiuno);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($ardiuno);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_ardiuno_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{ardiuno}/bewerken", methods={"GET", "POST"})
     * @Template()
     * @param Ardiuno $ardiuno
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function edit(Ardiuno $ardiuno, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $ardiuno);

        $form = $this->createForm(ArdiunoType::class, $ardiuno);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_ardiuno_index');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{ardiuno}/verwijderen", methods={"GET", "DELETE"})
     * @param Ardiuno $ardiuno
     */
    public function delete(Ardiuno $ardiuno)
    {
        $this->denyAccessUnlessGranted('delete', $ardiuno);

    }
}