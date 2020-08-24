<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Entity\GuildMember;
use Ares\Guild\Exception\GuildException;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Guild\Repository\GuildRepository;
use Ares\Room\Entity\Room;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class GuildController
 *
 * @package Ares\Guild\Controller
 */
class GuildController extends BaseController
{
    /**
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

    /**
     * @var GuildMemberRepository
     */
    private GuildMemberRepository $guildMemberRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * RoomController constructor.
     *
     * @param GuildRepository        $guildRepository
     * @param GuildMemberRepository  $guildMemberRepository
     * @param DoctrineSearchCriteria $searchCriteria
     */
    public function __construct(
        GuildRepository $guildRepository,
        GuildMemberRepository $guildMemberRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->guildRepository = $guildRepository;
        $this->guildMemberRepository = $guildMemberRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function guild(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->get($id);

        /** @var GuildMember $memberCount */
        $memberCount = $this->guildMemberRepository->count([
            'guild' => $id
        ]);

        if (is_null($guild)) {
            throw new GuildException(__('No specific Guild found'));
        }

        /** @var Room $guildRoom */
        $guildRoom = [
            'member_count' => $memberCount,
            'room' => [
                'name' => $guild->getRoom()->getName(),
                'description' => $guild->getRoom()->getDescription(),
                'state' => $guild->getRoom()->getState(),
                'users' => $guild->getRoom()->getUsers(),
                'users_max' => $guild->getRoom()->getUsersMax(),
                'score' => $guild->getRoom()->getScore(),
                'creator' => $guild->getRoom()->getOwner()->toArray()
            ]
        ];

        return $this->respond(
            $response,
            response()->setData(array_merge($guild->toArray(), $guildRoom))
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addOrder('id', 'DESC');

        $guilds = $this->guildRepository->paginate($this->searchCriteria);

        if ($guilds->isEmpty()) {
            throw new GuildException(__('No Guilds were found'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($guilds as $guild) {
            $guildRoom = [
                'room' => [
                    'name' => $guild->getRoom()->getName(),
                    'description' => $guild->getRoom()->getDescription(),
                    'state' => $guild->getRoom()->getState(),
                    'users' => $guild->getRoom()->getUsers(),
                    'users_max' => $guild->getRoom()->getUsersMax(),
                    'score' => $guild->getRoom()->getScore(),
                    'creator' => $guild->getRoom()->getOwner()->toArray()
                ]
            ];
            $list[] = array_merge($guild->toArray(), $guildRoom);
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function members(Request $request, Response $response, $args): Response
    {
        /** @var int $args */
        $id = $args['id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('guild', $id)
            ->addOrder('id', 'DESC');

        $members = $this->guildMemberRepository->paginate($this->searchCriteria);

        if ($members->isEmpty()) {
            throw new GuildException(__('No Members were found for this Guild'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($members as $member) {
            $list[] = $member->toArray();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
