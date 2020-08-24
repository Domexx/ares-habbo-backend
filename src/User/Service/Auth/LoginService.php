<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Auth;

use Ares\Ban\Entity\Ban;
use Ares\Ban\Exception\BanException;
use Ares\Ban\Repository\BanRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Service\TokenService;
use Ares\User\Entity\User;
use Ares\User\Exception\LoginException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class LoginService
 *
 * @package Ares\User\Service\Auth
 */
class LoginService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var BanRepository
     */
    private BanRepository $banRepository;

    /**
     * LoginService constructor.
     *
     * @param UserRepository $userRepository
     * @param BanRepository  $banRepository
     * @param TokenService   $tokenService
     */
    public function __construct(
        UserRepository $userRepository,
        BanRepository $banRepository,
        TokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->banRepository = $banRepository;
        $this->tokenService = $tokenService;
    }

    /**
     * Login user.
     *
     * @param string $username
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws BanException
     * @throws InvalidArgumentException
     * @throws LoginException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ValidateException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function login(string $username, string $password): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->getByUsername($username);

        if (empty($user) || !password_verify($password, $user->getPassword())) {
            throw new LoginException(__('general.failed'), 403);
        }

        /** @var Ban $isBanned */
        $isBanned = $this->banRepository->getBy([
            'user' => $user->getId()
        ], ['id' => 'DESC']);

        if (!is_null($isBanned) && $isBanned->getBanExpire() > time()) {
            throw new BanException(__('general.banned', [$isBanned->getBanReason()]), 401);
        }

        $user->setLastLogin(time());

        /** @var $user $this */
        $this->userRepository->update($user);

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return response()->setData([
            'token' => $token
        ]);
    }
}

