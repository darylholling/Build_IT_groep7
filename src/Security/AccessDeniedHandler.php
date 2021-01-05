<?php

namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AccessDeniedHandler
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @var EngineInterface|Environment
     */
    private $twig;

    /**
     * AccessDeniedHandler constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $response = new Response();
        $response->setContent($this->twig->render('security/access_denied.html.twig'));

        return $response;
    }
}