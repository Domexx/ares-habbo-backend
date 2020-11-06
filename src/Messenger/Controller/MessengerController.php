<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class MessengerController
 *
 * @package Ares\Messenger\Controller
 */
class MessengerController extends BaseController
{
    /**
     * @var MessengerRepository
     */
    private MessengerRepository $messengerRepository;

    /**
     * MessengerController constructor.
     *
     * @param MessengerRepository    $messengerRepository
     */
    public function __construct(
        MessengerRepository $messengerRepository
    ) {
        $this->messengerRepository = $messengerRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @param array $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws AuthenticationException
     */
    public function friends(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $user */
        $user = user($request);

        $friends = $this->messengerRepository
            ->getPaginatedMessengerFriends(
                $user->getId(),
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($friends)
        );
    }
}
