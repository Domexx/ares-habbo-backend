<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Guild\Repository\GuildRepository;
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
     * GuildController constructor.
     *
     * @param   GuildRepository         $guildRepository
     * @param   GuildMemberRepository   $guildMemberRepository
     */
    public function __construct(
        private GuildRepository $guildRepository,
        private GuildMemberRepository $guildMemberRepository
    ) {}

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAllGuilds(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $guilds = $this->guildRepository->getPaginatedGuildList($page, $resultPerPage);

        return $this->respond($response, response()->setData($guilds));
    }

    /**
     * Searches with term in groups, rooms and news.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function searchGuilds(Request $request, Response $response, array $args): Response
    {
        /** @var string $term */
        $term = $args['term'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $guilds = $this->guildRepository->searchGuilds($term, $page, $resultPerPage);

        return $this->respond($response, response()->setData($guilds));
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function getGuildById(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->getGuild($id);

        return $this->respond($response, response()->setData($guild));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getGuildMembers(Request $request, Response $response, array $args): Response
    {
        /** @var int $guildId */
        $guildId = $args['id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $members = $this->guildMemberRepository->getPaginatedGuildMembers($guildId, $page, $resultPerPage);

        return $this->respond($response, response()->setData($members));
    }

    /**
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
    */
    public function getMostMembersTop(Request $request, Response $response, array $args): Response
    {
        /** @var int $top */
        $top = $args['top'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->getMostMemberGuild($top);

        return $this->respond($response, response()->setData($guild));
    }
}
