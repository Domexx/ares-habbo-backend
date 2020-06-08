<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Slim\App;

return function (App $app) {
    $app->get('/', 'App\Controller\Status\StatusController:getStatus');
    $app->get('/user', 'App\Controller\User\UserController:all');
    $app->post('/login', 'App\Controller\Auth\AuthController:login');
    $app->post('/register', 'App\Controller\Auth\AuthController:register');
};
