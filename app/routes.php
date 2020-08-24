<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Enables Lazy CORS - Preflight Request
    $app->options('/{routes:.+}', function ($request, $response, $arguments) {
        return $response;
    });

    // Status
    $app->get('/', \Ares\Framework\Controller\Status\StatusController::class . ':getStatus');

    $app->group('/api/{locale}', function (RouteCollectorProxy $group) {

        // Only Accessible if LoggedIn
        $group->group('', function ($group) {
            // User
            $group->group('/user', function ($group) {
                $group->get('', \Ares\User\Controller\UserController::class . ':user');
                $group->post('/ticket', \Ares\User\Controller\AuthController::class . ':ticket');
                $group->post('/locale', \Ares\User\Controller\UserController::class . ':updateLocale');
            });

            // Articles
            $group->group('/articles', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':list');
                $group->get('/pinned', \Ares\Article\Controller\ArticleController::class . ':pinned');
                $group->get('/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':article');
            });

            // Guilds
            $group->group('/guilds', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':guild');
                $group->get('/members/{id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':members');
            });

            // Friends
            $group->group('/friends', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Messenger\Controller\MessengerController::class . ':friends');
                $group->post('/search/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Messenger\Controller\MessengerController::class . ':search');
            });

            // Rooms
            $group->group('/rooms', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':room');
            });

            // De-Authentication
            $group->post('/logout', \Ares\User\Controller\AuthController::class . ':logout');
        })->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        // Authentication
        $group->post('/login', \Ares\User\Controller\AuthController::class . ':login');
        $group->group('/register', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':register');
            $group->get('/looks', \Ares\User\Controller\AuthController::class . ':viableLooks');
        });

        // Global Routes
        $group->get('/user/online', \Ares\User\Controller\UserController::class . ':onlineUser');
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
