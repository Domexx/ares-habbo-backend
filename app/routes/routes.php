<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Enables Lazy CORS - Preflight Request
    $app->options('/{routes:.+}', function ($request, $response, $arguments) {
        return $response;
    });

    $app->group('/{locale}', function (RouteCollectorProxy $group) {

        // Only Accessible if LoggedIn
        $group->group('', function ($group) {
            // User
            $group->group('/user', function ($group) {
                $group->get('', \Ares\User\Controller\UserController::class . ':user');
                $group->put('/ticket', \Ares\User\Controller\AuthController::class . ':ticket');
                $group->put('/locale', \Ares\User\Controller\UserController::class . ':updateLocale');
                $group->get('/gift', \Ares\User\Controller\Gift\DailyGiftController::class . ':pick');
                $group->put('/currency', \Ares\User\Controller\UserCurrencyController::class . ':update')
                    ->setName('update-currency');
                $group->group('/settings', function ($group) {
                    $group->put('/change_general_settings', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeGeneralSettings');
                    $group->put('/change_password', \Ares\User\Controller\Settings\UserSettingsController::class . ':changePassword');
                    $group->put('/change_email', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeEmail');
                    $group->put('/change_username', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeUsername');
                });
            });

            // Articles
            $group->group('/articles', function ($group) {
                $group->post('/create', \Ares\Article\Controller\ArticleController::class . ':create')
                    ->setName('create-article');
                $group->put('/edit', \Ares\Article\Controller\ArticleController::class . ':editArticle')
                    ->setName('edit-article');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':list');
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':allList')->setName('view-all-articles');
                $group->get('/pinned/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':pinned');
                $group->get('/{slug}', \Ares\Article\Controller\ArticleController::class . ':article');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':delete')
                    ->setName('delete-article');
            });

            // Comments
            $group->group('/comments', function ($group) {
                $group->post('/create', \Ares\Article\Controller\CommentController::class . ':create');
                $group->put('/edit', \Ares\Article\Controller\CommentController::class . ':edit')
                    ->setName('edit-article-comment');
                $group->get('/{article_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Article\Controller\CommentController::class . ':list');
                $group->delete('/{id:[0-9]+}', \Ares\Article\Controller\CommentController::class . ':delete')
                    ->setName('delete-article-comment');
            });

            // Votes
            $group->group('/votes', function ($group) {
                $group->post('/create', \Ares\Vote\Controller\VoteController::class . ':create');
                $group->get('/total', \Ares\Vote\Controller\VoteController::class . ':getTotalVotes');
                $group->post('/delete', \Ares\Vote\Controller\VoteController::class . ':delete');
            });

            // Community
            $group->group('/community', function ($group) {
                $group->get('/search/rooms/{term}/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Community\Controller\CommunityController::class . ':searchRooms');
                $group->get('/search/guilds/{term}/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Community\Controller\CommunityController::class . ':searchGuilds');
                $group->get('/search/articles/{term}/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Community\Controller\CommunityController::class . ':searchArticles');
            });

            // Guilds
            $group->group('/guilds', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guild\Controller\GuildController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':guild');
                $group->get('/members/{guild_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guild\Controller\GuildController::class . ':members');
                $group->get('/most/members', \Ares\Guild\Controller\GuildController::class . ':mostMembers');
            });

            // Guestbook Entries
            $group->group('/guestbook', function ($group) {
                $group->post('/create', \Ares\Guestbook\Controller\GuestbookController::class . ':create');
                $group->get('/profile/{profile_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guestbook\Controller\GuestbookController::class . ':profileList');
                $group->get('/guild/{guild_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Guestbook\Controller\GuestbookController::class . ':guildList');
                $group->delete('/{id:[0-9]+}', \Ares\Guestbook\Controller\GuestbookController::class . ':delete')
                    ->setName('delete-guestbook-entry');
            });

            // Friends
            $group->group('/friends', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Messenger\Controller\MessengerController::class . ':friends');
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
                $group->get('/top-duckets', \Ares\User\Controller\UserHallOfFameController::class . ':topDuckets');
                $group->get('/top-online-time',
                    \Ares\User\Controller\UserHallOfFameController::class . ':topOnlineTime');
                $group->get('/top-achievement',
                    \Ares\User\Controller\UserHallOfFameController::class . ':topAchievement');
            });

            // Photos
            $group->group('/photos', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Photo\Controller\PhotoController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':photo');
                $group->post('/search', \Ares\Photo\Controller\PhotoController::class . ':search');
                $group->delete('/{id:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':delete')
                    ->setName('delete-photo');
            });

            // Profiles
            $group->group('/profiles', function ($group) {
                $group->get('/{profile_id:[0-9]+}/badges/slot',
                    \Ares\Profile\Controller\ProfileController::class . ':slotBadges');
                $group->get('/{profile_id:[0-9]+}/badges/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':badgeList');
                $group->get('/{profile_id:[0-9]+}/friends/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':friendList');
                $group->get('/{profile_id:[0-9]+}/guilds/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':guildList');
                $group->get('/{profile_id:[0-9]+}/rooms/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':roomList');
                $group->get('/{profile_id:[0-9]+}/photos/list/{page:[0-9]+}/{rpp:[0-9]+}',
                    \Ares\Profile\Controller\ProfileController::class . ':photoList');
            });

            // Habbo Permissions
            $group->group('/permissions', function ($group) {
                $group->get('/staff/list', \Ares\Permission\Controller\PermissionController::class . ':listUserWithRank');
            });

            // Roles, Permissions
            $group->group('/roles', function ($group) {
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Role\Controller\RoleController::class . ':list')->setName('list-all-roles');

                $group->group('/create', function ($group) {
                    $group->post('/role', \Ares\Role\Controller\RoleController::class . ':createRole')->setName('create-role');
                    $group->post('/permission', \Ares\Role\Controller\RolePermissionController::class . ':createPermission')->setName('create-permission');
                });
                
                $group->group('/assign', function ($group) {
                    $group->post('/rank', \Ares\Role\Controller\RoleController::class . ':assignRole')->setName('assign-role');
                    $group->post('/child', \Ares\Role\Controller\RoleController::class . ':createChildRole')->setName('create-child-role');
                    $group->post('/permission', \Ares\Role\Controller\RolePermissionController::class . ':createRolePermission')->setName('create-role-permission');
                });
                
                $group->group('/delete', function ($group) {
                    $group->delete('/role', \Ares\Role\Controller\RoleController::class . ':deleteRole')->setName('delete-role');
                    $group->delete('/permission', \Ares\Role\Controller\RolePermissionController::class . ':deleteRolePermission')->setName('delete-role-permission');
                });

                $group->group('/permissions', function ($group) {
                    $group->get('/list', \Ares\Role\Controller\RolePermissionController::class . ':list')->setName('list-all-permissions');
                });

                $group->group('/view', function($group) {
                    $group->get('/my-permissions', \Ares\Role\Controller\RolePermissionController::class . ':userPermissions');
                    $group->get('/role-tree', \Ares\Role\Controller\RoleController::class . ':treeView');
                    $group->get('/categories', \Ares\Role\Controller\RoleController::class . ':roleCategories');
                    $group->get('/category/{id:[0-9]+}', \Ares\Role\Controller\RoleController::class . ':categoryById');
                    $group->get('/role/{id:[0-9]+}', \Ares\Role\Controller\RoleController::class . ':roleById');
                });
            });

            // Payments
            $group->group('/payments', function ($group) {
                $group->post('/create', \Ares\Payment\Controller\PaymentController::class . ':create');
                $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':list');
                $group->get('/{id:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':payment');
                $group->delete('/{id:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':delete')
                    ->setName('delete-payment');
            });

            // Forum
            $group->group('/forum', function ($group) {
                $group->group('/comments', function ($group) {
                    $group->post('/{thread:[0-9]+}/create', \Ares\Forum\Controller\CommentController::class . ':create');
                    $group->get('/{thread:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\CommentController::class . ':list');
                    $group->put('/{thread:[0-9]+}/{id:[0-9]+}', \Ares\Forum\Controller\CommentController::class . ':edit');
                    $group->delete('/{thread:[0-9]+}/{id:[0-9]+}', \Ares\Forum\Controller\CommentController::class . ':delete')
                        ->setName('delete-forum-comment');
                });
                $group->group('/topics', function ($group) {
                    $group->post('/create', \Ares\Forum\Controller\TopicController::class . ':create')
                        ->setName('create-forum-topic');
                    $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\TopicController::class . ':list');
                    $group->put('{id:[0-9]+}', \Ares\Forum\Controller\TopicController::class . ':edit')
                        ->setName('edit-forum-topic');
                    $group->delete('{id:[0-9]+}', \Ares\Forum\Controller\TopicController::class . ':delete')
                        ->setName('delete-forum-topic');
                });
                $group->group('/threads', function ($group) {
                    $group->post('/create', \Ares\Forum\Controller\ThreadController::class . ':create');
                    $group->get('/{topic_id:[0-9]+}/list/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Forum\Controller\ThreadController::class . ':list');
                    $group->get('/{topic_id:[0-9]+}/{slug}', \Ares\Forum\Controller\ThreadController::class . ':thread');
                    $group->put('/{id:[0-9]+}', \Ares\Forum\Controller\ThreadController::class . ':edit');
                    $group->delete('/{topic:[0-9]+}/{id:[0-9]+}', \Ares\Forum\Controller\ThreadController::class . ':delete')
                        ->setName('delete-forum-thread');
                });
            });

            $group->group('/rcon', function ($group) {
                $group->post('/execute', \Ares\Rcon\Controller\RconController::class . ':executeCommand')
                    ->setName('execute-rcon-command');
            });

            // Gets updated UserOfTheHotel
            $group->get('/user-of-the-hotel',
                \Ares\User\Controller\UserOfTheHotelController::class . ':getUserOfTheHotel'
            );

            // De-Authentication
            $group->post('/logout', \Ares\User\Controller\AuthController::class . ':logout');
        })->add(\Ares\Role\Middleware\RolePermissionMiddleware::class)
            ->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        // Authentication
        $group->group('/login', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':login');
            $group->post('/look', \Ares\User\Controller\UserController::class . ':getLook');
        });

        $group->group('/register', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':register');
            $group->get('/looks', \Ares\User\Controller\AuthController::class . ':viableLooks');
        });

        // Global Settings
        $group->group('/settings', function ($group) {
            $group->get('/list/{page:[0-9]+}/{rpp:[0-9]+}',
                \Ares\Setting\Controller\SettingController::class . ':list');
            $group->post('/get', \Ares\Setting\Controller\SettingController::class . ':get');
            $group->put('/set', \Ares\Setting\Controller\SettingController::class . ':set')
                ->setName('set-global-setting');
        });

        // Global Routes
        $group->get('/user/online', \Ares\User\Controller\UserController::class . ':onlineUser');
    })->add(\Ares\Framework\Middleware\LocaleMiddleware::class)
        ->add(\Ares\Framework\Middleware\ThrottleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
