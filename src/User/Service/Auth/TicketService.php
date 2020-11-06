<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Auth;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Exception;

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
     * @param User $user
     *
     * @return string
     * @throws DataObjectManagerException
     * @throws Exception
     */
    public function generate(User $user): string
    {
        $ticket = $this->hash($user);
        $user->setAuthTicket($ticket);

        $this->userRepository->save($user);

        return $ticket;
    }

    /**
     * Hashes our User object to a Ticket and returns it
     *
     * @param   object  $user
     *
     * @return string
     * @throws Exception
     */
    public function hash(object $user): string
    {
        return hash(
            'sha256',
            $user->getUsername() . random_int(1337, 2337) . '-' . $_ENV["WEB_NAME"],
            false
        );
    }
}
