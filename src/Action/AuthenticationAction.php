<?php

namespace LolAuthentifier\Action;

use LolAuthentifier\Helper\AuthenticationHelper;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class AuthenticationAction
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var AuthenticationHelper
     */
    private $authenticationHelper;

    /**
     * AuthenticationAction constructor.
     * @param Twig $twig
     * @param AuthenticationHelper $authenticationHelper
     */
    public function __construct(Twig $twig, AuthenticationHelper $authenticationHelper)
    {
        $this->twig = $twig;
        $this->authenticationHelper = $authenticationHelper;
    }

    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        if ($request->getParsedBodyParam('username')
            && $this->authenticationHelper->isAuthenticated($request->getParsedBodyParam('username'))
        ) {
            return $this->twig->render($response, 'authenticated.html.twig');
        }

        //TODO How to rebuild secretKey without taking all dependencies
        return $response->withRedirect('/');
    }
}