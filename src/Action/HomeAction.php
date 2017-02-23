<?php

namespace LolAuthentifier\Action;

use LolAuthentifier\Helper\AuthenticationHelper;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class HomeAction
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
     * HomeAction constructor.
     * @param Twig $twig
     * @param AuthenticationHelper $authenticationHelper
     */
    public function __construct(Twig $twig, AuthenticationHelper $authenticationHelper)
    {
        $this->twig = $twig;
        $this->authenticationHelper = $authenticationHelper;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
       return $this->twig->render($response, 'home.html.twig', [
           'secretKey' => $this->authenticationHelper->initializeSecretKey(),
       ]);
    }
}
