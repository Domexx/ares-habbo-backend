<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Controller;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class BaseController
 *
 * @package Ares\Framework\Controller
 */
abstract class BaseController
{
    /**
     * If the user is in the JWT Token data its id is returned
     *
     * @param Request $request
     * @return int|null
     */
    protected function authUser(Request $request): ?int
    {
        /** @var array $user */
        $user = $request->getAttribute('ares_uid');
        if (isset($user)) {
            return json_decode(json_encode($user), true);
        }

        return null;
    }

    /**
     * @param UserRepository $userRepository
     * @param Request        $request
     *
     * @param bool           $cachedEntity
     *
     * @return object
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     */
    protected function getUser(UserRepository $userRepository, Request $request, bool $cachedEntity = true): object
    {
        /** @var array $authUser */
        $authUser = $this->authUser($request);

        /** @var User $user */
        $user = $userRepository->get((int)$authUser, $cachedEntity);

        if (!$user) {
            throw new UserException(__('User doesnt exists.'), 404);
        }

        return $user;
    }

    /**
     * Determines the RealIP of the User and returns it
     *
     * @return string|null Returns the current User IP when given
     */
    protected function determineIp(): ?string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Creates json response.
     *
     * @param Response $response The current Response
     * @param mixed $customResponse The Data given into the Function
     * @return Response Returns a Response with the given Data
     */
    protected function respond(Response $response, CustomResponseInterface $customResponse): Response
    {
        $response->getBody()->write($customResponse->getJson());

        return $response
            ->withStatus(
                $customResponse
                    ->getCode()
            )
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}
