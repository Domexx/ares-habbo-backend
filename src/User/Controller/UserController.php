<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 *
 * @package Ares\User\Controller
 */
class UserController extends BaseController
{
    /**
     * 2 equals the second field in the enum-field
     */
    private const USER_EQUALS_ONLINE = 2;

    /**
     * @var UserRepository Gets the current UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     *
     * @return Response Returns a Response with the given Data
     * @throws UserException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function user(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request, false)
            ->toArray();

        return $this->respond(
            $response,
            response()->setData($user)
        );
    }

    /**
     * Gets all current Online User and counts them
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function onlineUser(Request $request, Response $response): Response
    {
        /** @var User $onlineUser */
        $onlineUser = $this->userRepository->count([
            'online' => self::USER_EQUALS_ONLINE
        ]);

        return $this->respond(
            $response,
            response()->setData([
                'count' => $onlineUser
            ])
        );
    }

    /**
     * Saves the given Language to the User
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     * @throws OptimisticLockException
     */
    public function updateLocale(Request $request, Response $response): Response
    {
        /** @var array $body */
        $body = $request->getParsedBody();

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request, false);
        $user->setLocale($body['locale']);

        $this->userRepository->update($user);

        return $this->respond(
            $response,
            response()->setData($user->toArray())
        );
    }
}
