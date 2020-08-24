<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Auth;

use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class TicketService
 *
 * @package Ares\User\Service\Auth
 */
class TicketService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * TicketService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Generates us a new Ticket for the User to authenticate him to the Server
     *
     * @param $user
     *
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function generate(object $user): string
    {
        $ticket = $this->hash($user);
        $user->setTicket($ticket);

        $this->userRepository->update($user);

        return $ticket;
    }

    /**
     * Hashes our User object to a Ticket and returns it
     *
     * @param object $user
     *
     * @return string
     */
    public function hash(object $user): string
    {
        return hash('sha256', $user->getUsername() . rand(1337, 2337) . '-' . $_ENV["WEB_NAME"], false);
    }
}
