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

    $app->group('/{locale}', function (RouteCollectorProxy $group) {

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
                $group->post('/create', \Ares\Article\Controller\ArticleController::class . ':create');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':list');
                $group->get('/pinned', \Ares\Article\Controller\ArticleController::class . ':pinned');
                $group->get('/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':article');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':delete');
            });

            // Comments
            $group->group('/comments', function ($group) {
                $group->post('/create', \Ares\Article\Controller\CommentController::class . ':create');
                $group->post('/edit', \Ares\Article\Controller\CommentController::class . ':edit');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\CommentController::class . ':list');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\CommentController::class . ':delete');
            });

            // Votes
            $group->group('/votes', function ($group) {
                $group->post('/create', \Ares\Vote\Controller\VoteController::class . ':create');
                $group->get('/likes/{vote_entity:[0-9]+}/{entity_id:[0-9]+}', \Ares\Vote\Controller\VoteController::class . ':getTotalLikes');
                $group->get('/dislikes/{vote_entity:[0-9]+}/{entity_id:[0-9]+}', \Ares\Vote\Controller\VoteController::class . ':getTotalDislikes');
                $group->post('/delete', \Ares\Vote\Controller\VoteController::class . ':delete');
            });

            // Guilds
            $group->group('/guilds', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':guild');
                $group->get('/members/{id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':members');
                $group->get('/most/members', \Ares\Guild\Controller\GuildController::class . ':mostMembers');
            });

            // Friends
            $group->group('/friends', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Messenger\Controller\MessengerController::class . ':friends');
            });

            // Rooms
            $group->group('/rooms', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':room');
                $group->get('/most/visited', \Ares\Room\Controller\RoomController::class . ':mostVisited');
            });

            // Hall-Of-Fame
            $group->group('/hall-of-fame', function ($group) {
                $group->get('/top-credits', \Ares\User\Controller\UserHallOfFameController::class . ':topCredits');
                $group->get('/top-diamonds', \Ares\User\Controller\UserHallOfFameController::class . ':topDiamonds');
                $group->get('/top-pixels', \Ares\User\Controller\UserHallOfFameController::class . ':topPixels');
                $group->get('/top-online-time', \Ares\User\Controller\UserHallOfFameController::class . ':topOnlineTime');
                $group->get('/top-achievement', \Ares\User\Controller\UserHallOfFameController::class . ':topAchievement');
            });

            // Photos
            $group->group('/photos', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':photo');
                $group->post('/search', \Ares\Photo\Controller\PhotoController::class . ':search');
                $group->delete('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':delete');
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

        // Global Settings
        $group->group('/settings', function ($group) {
            $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Settings\Controller\SettingsController::class . ':list');
            $group->post('/get', \Ares\Settings\Controller\SettingsController::class . ':get');
            $group->post('/set', \Ares\Settings\Controller\SettingsController::class . ':set');
        });

        // Global Routes
        $group->get('/user/online', \Ares\User\Controller\UserController::class . ':onlineUser');
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
