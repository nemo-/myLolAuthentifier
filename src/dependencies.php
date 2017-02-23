<?php
// DIC configuration

use GuzzleHttp\Client;
use LolAuthentifier\Action\AuthenticationAction;
use LolAuthentifier\Action\HomeAction;
use LolAuthentifier\Helper\AuthenticationHelper;
use LolAuthentifier\Repository\LolApiRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RandomLib\Factory;
use RandomLib\Generator;
use SlimSession\Helper;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../templates', [
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

// Actions
$container[HomeAction::class] = function ($container) {
    return new HomeAction($container['view'], $container[AuthenticationHelper::class]);
};

$container[AuthenticationAction::class] = function ($container) {
    return new AuthenticationAction($container['view'], $container[AuthenticationHelper::class]);
};

// Helper
$container[AuthenticationHelper::class] = function ($container) {
    return new AuthenticationHelper($container[LolApiRepository::class], $container[Helper::class], $container[Generator::class], 25);
};

// Repository
$container[LolApiRepository::class] = function ($container) {
    return new LolApiRepository($container[Client::class]);
};

// Bundles
$container[Client::class] = function () {
    return new Client(['base_uri' => 'https://euw.api.pvp.net', 'verify' => false, 'query' => ['api_key' => 'RGAPI-cec99db0-3221-487d-b14b-0e506b8a42c1']]);
};

$container[Generator::class] = function () {
    return (new Factory())->getMediumStrengthGenerator();
};

$container[Helper::class] = function () {
    return new Helper;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
