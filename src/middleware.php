<?php
// Application middleware

use Slim\Middleware\Session;

$app = new \Slim\App;
$app->add(new Session([
    'name' => 'dummy_session',
    'autorefresh' => true,
    'lifetime' => '1 hour'
]));