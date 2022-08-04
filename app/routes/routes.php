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

    $app->group('', function (RouteCollectorProxy $group) {

        // Only Accessible if LoggedIn
        $group->group('', function ($group) {

            // Articles
            $group->group('/articles', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':getAllArticles')->setName('view-all-articles');
                $group->get('/search/any/{term}/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':searchAnyArticles')->setName('view-all-articles');
                $group->get('/view/{id:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':getAnyArticleById')->setName('view-all-articles');
                $group->post('', \Ares\Article\Controller\ArticleController::class . ':createArticle')->setName('create-articles');

                $group->group('/{id:[0-9]+}', function ($group) {
                    // Comments
                    $group->group('/comments', function ($group) {
                        $group->post('', \Ares\Article\Controller\CommentController::class . ':createComment');
                        $group->put('', \Ares\Article\Controller\CommentController::class . ':editComment');
                        $group->delete('', \Ares\Article\Controller\CommentController::class . ':deleteComment');
                    });

                    $group->put('', \Ares\Article\Controller\ArticleController::class . ':editArticle')->setName('edit-articles');
                    $group->delete('', \Ares\Article\Controller\ArticleController::class . ':deleteArticle')->setName('delete-articles');
                });
            });

            // Guestbook Entries
            $group->group('/guestbook', function ($group) {
                $group->post('', \Ares\Guestbook\Controller\GuestbookController::class . ':createEntry');
                $group->put('', \Ares\Guestbook\Controller\GuestbookController::class . ':editEntry');
                $group->delete('', \Ares\Guestbook\Controller\GuestbookController::class . ':deleteEntry');
            });

            // Payments
            $group->group('/payments', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Payment\Controller\PaymentController::class . ':getAllPayments')->setName('view-all-payments');
                $group->post('/create', \Ares\Payment\Controller\PaymentController::class . ':createPayment');

                $group->group('/{id:[0-9]+}', function ($group) {
                    $group->get('', \Ares\Payment\Controller\PaymentController::class . ':getPaymentById');
                    $group->put('', \Ares\Payment\Controller\PaymentController::class . ':updatePayment');
                });
            });

            // Photos
            $group->group('/photos/{photo_id:[0-9]+}', function ($group) {
                $group->put('', \Ares\Photo\Controller\PhotoController::class . ':hidePhoto')->setName('hide-photos');
                $group->delete('', \Ares\Photo\Controller\PhotoController::class . ':deletePhoto')->setName('delete-photos');
            });

            // Ranks
            $group->group('/ranks', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Rank\Controller\RankController::class . ':getAllRanks')->setName('view-all-ranks');
                $group->get('', \Ares\Rank\Controller\RankController::class . ':getRanksList')->setName('view-all-ranks');
                $group->get('/columns', \Ares\Rank\Controller\RankController::class . ':getRankColumns')->setName('view-all-ranks');
                $group->post('', \Ares\Rank\Controller\RankController::class . ':createRank')->setName('create-ranks');

                $group->group('/{id:[0-9]+}', function ($group) {
                    $group->get('', \Ares\Rank\Controller\RankController::class . ':getRankById')->setName('view-all-ranks');
                    $group->put('', \Ares\Rank\Controller\RankController::class . ':editRank')->setName('edit-ranks');
                });
            });

            // Roles
            $group->group('/roles', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Role\Controller\RoleController::class . ':getAllRoles')->setName('view-all-roles');
                $group->get('/available/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Role\Controller\RoleController::class . ':availableRoles');
                $group->post('', \Ares\Role\Controller\RoleController::class . ':createRole')->setName('create-roles');

                $group->group('/{id:[0-9]+}', function ($group) {
                    $group->get('', \Ares\Role\Controller\RoleController::class . ':getRoleById')->setName('view-all-roles');
                    $group->put('', \Ares\Role\Controller\RoleController::class . ':editRole')->setName('edit-roles');
                    $group->put('/toggle-permission/{role_permission_id:[0-9]+}', \Ares\Role\Controller\RolePermissionController::class . ':toggleRolePermission')->setName('edit-roles');
                    $group->put('/clear-permissions', \Ares\Role\Controller\RolePermissionController::class . ':clearRolePermissions');
    
                    $group->group('/role-rank', function ($group) {
                        $group->post('', \Ares\Role\Controller\RoleController::class . ':createRoleRank')->setName('create-roles');
                        $group->put('', \Ares\Role\Controller\RoleController::class . ':editRoleRank')->setName('edit-roles');
                        $group->delete('', \Ares\Role\Controller\RoleController::class . ':deleteRoleRank')->setName('delete-roles');
                    });
                });

                $group->group('/role-hierarchy', function ($group) {
                    $group->get('/role-tree', \Ares\Role\Controller\RoleController::class . ':getRoleTree');
                    $group->get('/available-role-tree', \Ares\Role\Controller\RoleController::class . ':getAvailableRoleTree');
                    $group->post('', \Ares\Role\Controller\RoleController::class . ':createRoleHierarchy')->setName('create-roles');
                    $group->put('', \Ares\Role\Controller\RoleController::class . ':editRoleHierarchy')->setName('edit-roles');
                    $group->delete('', \Ares\Role\Controller\RoleController::class . ':deleteRoleHierarchy')->setName('delete-roles');
                });

                $group->group('/role-permissions', function ($group) {
                    $group->get('/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Role\Controller\RolePermissionController::class . ':getAllRolePermissions')->setName('view-all-role-permissions');
                    $group->get('', \Ares\Role\Controller\RolePermissionController::class . ':getRolePermissionsList')->setName('view-all-roles');
                    $group->post('', \Ares\Role\Controller\RolePermissionController::class . ':createRolePermission')->setName('create-role-permissions');

                    $group->group('/{role_permission_id:[0-9]+}', function ($group) {
                        $group->put('', \Ares\Role\Controller\RolePermissionController::class . ':editRolePermission')->setName('edit-role-permissions');
                        $group->delete('', \Ares\Role\Controller\RolePermissionController::class . ':deleteRolePermission')->setName('delete-role-permissions');
                    });
                });
            });

            // Shop
            $group->group('/shop', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Shop\Controller\ShopController::class . ':getAllOffers')->setName('view-all-offers');
                $group->get('/available/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Shop\Controller\ShopController::class . ':availableOffers');
                $group->post('', \Ares\Shop\Controller\ShopController::class . ':createOffer')->setName('create-offers');

                $group->group('/{id:[0-9]+}', function ($group) {
                    $group->get('', \Ares\Shop\Controller\ShopController::class . ':getOfferById');
                    $group->get('/payments', \Ares\Payment\Controller\PaymentController::class . ':getOfferPayments');
                    $group->put('', \Ares\Shop\Controller\ShopController::class . ':editOffer')->setName('edit-offers');
                    $group->delete('', \Ares\Shop\Controller\ShopController::class . ':deleteOffer')->setName('delete-offers');
                });
            });

            // Authenticated User
            $group->group('/user', function ($group) {
                $group->get('', \Ares\User\Controller\UserController::class . ':getLoggedUser');
                $group->get('/ticket', \Ares\User\Controller\AuthController::class . ':getLoggedTicket');
                
                $group->group('/change', function ($group) {
                    $group->put('/general-settings', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeGeneralSettings');
                    $group->put('/password', \Ares\User\Controller\Settings\UserSettingsController::class . ':changePassword');
                    $group->put('/email', \Ares\User\Controller\Settings\UserSettingsController::class . ':changeEmail');
                });

                // Role Permissions
                $group->get('/permissions', \Ares\User\Controller\UserController::class. ':getLoggedPermissions');

                //Badge Slots
                $group->get('/badges/slots', \Ares\User\Controller\UserController::class . ':getLoggedBadgeSlots');

                //Votes History
                $group->get('/votes-history', \Ares\User\Controller\UserController::class . ':getLoggedVotesHistory');

                // Friends
                $group->get('/friends/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Messenger\Controller\MessengerController::class . ':getLoggedFriends');
            });

            //Users
            $group->group('/users', function($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\User\Controller\UserController::class . ':getAllUsers')->setName('view-all-users');
            
                $group->group('/{id:[0-9]+}', function($group) {
                    $group->get('', \Ares\User\Controller\UserController::class . ':getUserById')->setName('view-all-users');
                    $group->get('/payments', \Ares\Payment\Controller\PaymentController::class . ':getUserPayments')->setName('view-all-users');
                    $group->get('/votes-history', \Ares\User\Controller\UserController::class . ':getUserVotesHistory')->setName('view-all-users');

                    $group->group('/update', function ($group) {
                        $group->put('/rank', \Ares\User\Controller\UserController::class . ':updateUserRank')->setName('edit-users');
                        $group->put('/username', \Ares\User\Controller\UserController::class . ':updateUserName')->setName('edit-users');
                        $group->put('/reset-password', \Ares\User\Controller\UserController::class . ':resetUserPassword')->setName('edit-users');
                    });

                    $group->group('/currency', function($group) {
                    });
                });


                $group->group('/registers', function($group) {
                    $group->get('/total', \Ares\User\Controller\UserController::class . ':getTotalRegistersCount');
                    $group->get('/ad-total', \Ares\User\Controller\UserController::class . ':getAdTotalRegistersCount');
                    $group->get('/weekly', \Ares\User\Controller\UserController::class . ':getWeeklyRegistersCount');
                    $group->get('/monthly', \Ares\User\Controller\UserController::class . ':getMonthlyRegistersCount');
                    $group->get('/yearly', \Ares\User\Controller\UserController::class . ':getYearlyRegistersCount');
                });
            });

            // Votes
            $group->group('/votes', function ($group) {
                $group->post('', \Ares\Vote\Controller\VoteController::class . ':createVote');
                $group->delete('', \Ares\Vote\Controller\VoteController::class . ':deleteVote');
            });

            // RCON
            $group->group('/rcon', function ($group) {
                $group->post('/execute', \Ares\Rcon\Controller\RconController::class . ':executeCommand');
            });

            // Server
            $group->group('/server', function ($group) {
                $group->group('/nitro-texts', function ($group) {
                    $group->get('', \Ares\Server\Controller\ServerController::class . ':getNitroTexts')->setName('manage-nitro-texts');
                    $group->put('', \Ares\Server\Controller\ServerController::class . ':editNitroTexts')->setName('manage-nitro-texts');
                });

                $group->group('/badges', function ($group) {
                    $group->get('/exists/{code}', \Ares\Server\Controller\ServerController::class . ':verifyBadgeCode');
                    $group->post('/upload', \Ares\Server\Controller\ServerController::class . ':uploadBadge')->setName('upload-badges');
                });
            });

            // Web Settings
            $group->group('/settings', function ($group) {
                $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Setting\Controller\SettingController::class . ':getAllSettings')->setName('view-all-settings');
                $group->put('', \Ares\Setting\Controller\SettingController::class . ':editSetting')->setName('edit-settings');
            });

            //Polaris
            $group->group('/polaris', function ($group) {
                $group->group('/peaks', function ($group) {
                    $group->get('/weekly', \Ares\Polaris\Controller\PolarisController::class . ':getWeekly');
                    $group->get('/monthly', \Ares\Polaris\Controller\PolarisController::class . ':getMonthly');
                    $group->get('/yearly', \Ares\Polaris\Controller\PolarisController::class . ':getYearly');
                });
            });

            // De-Authentication
            $group->post('/logout', \Ares\User\Controller\AuthController::class . ':logout');

        })->add(\Ares\Role\Middleware\RolePermissionMiddleware::class)
            ->add(\Ares\Framework\Middleware\AuthMiddleware::class);

        // Authentication
        $group->group('/login', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':login');
        });

        // Articles
        $group->group('/articles', function ($group) {
            $group->get('/available/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':getAvailableArticles');
            $group->get('/pinned/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':getPinnedArticles');
            $group->get('/search/{term}/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\ArticleController::class . ':searchArticles');

            $group->group('/{id:[0-9]+}', function ($group) {
                $group->get('', \Ares\Article\Controller\ArticleController::class . ':getArticleById');

                // Comments
                $group->get('/comments/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Article\Controller\CommentController::class . ':getArticleComments');
            });
        });

        // Guilds
        $group->group('/guilds', function ($group) {
            $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':getAllGuilds');
            $group->get('/search/{term}/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':searchGuilds');

            $group->group('/{id:[0-9]+}', function ($group) {
                $group->get('', \Ares\Guild\Controller\GuildController::class . ':getGuildById');
                $group->get('/members/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':getGuildMembers');
                $group->get('/guestbook/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guestbook\Controller\GuestbookController::class . ':getGuildGuestbookEntries');
            });

            $group->get('/top/members/{top:[0-9]+}', \Ares\Guild\Controller\GuildController::class . ':getMostMembersTop');
        });

        // Photos
        $group->group('/photos', function ($group) {
            $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Photo\Controller\PhotoController::class . ':getAllPhotos');
        });

        // Profiles
        $group->group('/profile', function ($group) {
            $group->group('/{username}', function ($group) {
                $group->get('', \Ares\Profile\Controller\ProfileController::class . ':getProfile');
                $group->get('/badges/slots', \Ares\Profile\Controller\ProfileController::class . ':getBadgeSlots');
                $group->get('/badges/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Profile\Controller\ProfileController::class . ':getBadges');
                $group->get('/friends/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Profile\Controller\ProfileController::class . ':getProfileFriends');
                $group->get('/guilds/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Profile\Controller\ProfileController::class . ':getGuilds');
                $group->get('/rooms/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Profile\Controller\ProfileController::class . ':getRooms');
                $group->get('/photos/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Profile\Controller\ProfileController::class . ':getPhotos');
                $group->get('/guestbook/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Guestbook\Controller\GuestbookController::class . ':getProfileGuestbookEntries');
            });
        });

        // Registration
        $group->group('/register', function ($group) {
            $group->post('', \Ares\User\Controller\AuthController::class . ':register');
            $group->get('/looks', \Ares\User\Controller\AuthController::class . ':registerLooks');
        });

        // Rooms
        $group->group('/rooms', function ($group) {
            $group->get('/all/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':getAllRooms');
            $group->get('/search/{term}/{page:[0-9]+}/{rpp:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':searchRooms');
            $group->get('/{id:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':getRoomById');
            $group->get('/top/visited/{top:[0-9]+}', \Ares\Room\Controller\RoomController::class . ':getMostVisitedTop');
        });

        // Web Settings
        $group->group('/settings', function ($group) {
            $group->get('/{key}', \Ares\Setting\Controller\SettingController::class . ':getSettingByKey');
            $group->get('/multiple/{keys}', \Ares\Setting\Controller\SettingController::class . ':getMultipleSettings');
        });

        // Users
        $group->group('/users', function($group) {
            $group->get('/random/{count:[0-9]+}', \Ares\User\Controller\UserController::class . ':getRandomUsers');
            $group->get('/look/{username}', \Ares\User\Controller\UserController::class . ':getLookByUsername');
            $group->get('/online-count', \Ares\User\Controller\UserController::class . ':getOnlineCount');

            $group->group('/top', function($group) {
                $group->get('/currency/{type}', \Ares\User\Controller\UserHallOfFameController::class . ':getTopCurrency');
                $group->get('/online-time', \Ares\User\Controller\UserHallOfFameController::class . ':getTopOnlineTime');
                $group->get('/achievements', \Ares\User\Controller\UserHallOfFameController::class . ':getTopAchievements');
                $group->get('/user-of-the-hotel', \Ares\User\Controller\UserOfTheHotelController::class . ':getUserOfTheHotel');
            });
        });

        $group->get('/test', \Ares\User\Controller\UserController::class . ':test');

    })->add(\Ares\Framework\Middleware\ThrottleMiddleware::class);

    // Catches every route that is not found
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    });
};
