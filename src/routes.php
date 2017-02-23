<?php
// Routes

use LolAuthentifier\Action\AuthenticationAction;
use LolAuthentifier\Action\HomeAction;

$app->get('/', HomeAction::class);
$app->post('/', AuthenticationAction::class);
