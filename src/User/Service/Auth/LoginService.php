<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Auth;

use Ares\Ban\Entity\Ban;
use Ares\Ban\Exception\BanException;
use Ares\Ban\Repository\BanRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Factory\DataObjectManagerFactory;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Service\TokenService;
use Ares\User\Entity\User;
use Ares\User\Exception\LoginException;
use Ares\User\Repository\UserRepository;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class LoginService
 *
 * @package Ares\User\Service\Auth
 */
class LoginService
{
    /**
     * LoginService constructor.
     *
     * @param UserRepository           $userRepository
     * @param BanRepository            $banRepository
     * @param TokenService             $tokenService
     * @param DataObjectManagerFactory $dataObjectManagerFactory
     */
    public function __construct(
        private UserRepository $userRepository,
        private BanRepository $banRepository,
        private TokenService $tokenService,
        private DataObjectManagerFactory $dataObjectManagerFactory
    ) {}

    /**
     * Login user.
     *
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws BanException
     * @throws DataObjectManagerException
     * @throws LoginException
     * @throws ValidateException
     * @throws NoSuchEntityException
     */
    public function login(array $data): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->get($data['username'], 'username', true);

        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            throw new LoginException(__('general.failed'), 403);
        }

        /** @var Ban $isBanned */
        $isBanned = $this->banRepository->get($user->getId(), 'user_id', true);

        if ($isBanned && $isBanned->getBanExpire() > time()) {
            throw new BanException(__('You are banned because of %s', [$isBanned->getBanReason()]), 401);
        }

        $user->setLastLogin(time());
        $user->setIpCurrent($data['ip_current']);

        $this->userRepository->save($user);

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return response()
            ->setData([
                'token' => $token
            ]);
    }
}

