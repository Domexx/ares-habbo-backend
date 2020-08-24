<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Messenger\Exception\MessengerException;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
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
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * MessengerController constructor.
     *
     * @param   MessengerRepository     $messengerRepository
     * @param   UserRepository          $userRepository
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        MessengerRepository $messengerRepository,
        UserRepository $userRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->messengerRepository = $messengerRepository;
        $this->userRepository = $userRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @param             $args
     *
     * @return Response
     * @throws MessengerException
     * @throws UserException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function friends(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('user', $this->getUser($this->userRepository, $request)->getId())
            ->addOrder('id', 'DESC');

        $friends = $this->messengerRepository->paginate($this->searchCriteria);

        if ($friends->isEmpty()) {
            throw new MessengerException(__('You have no friends'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($friends as $friend) {
            $list[] = $friend
                ->getFriend()
                ->toArray();
        }

        return $this->respond(
            $response,
            response()->setData([
                'pagination' => [
                    'totalPages' => $friends->getPages(),
                    'prevPage' => $friends->getPrevPage(),
                    'nextPage' => $friends->getNextPage()
                ],
                'friends' => $list
            ])
        );
    }
}
