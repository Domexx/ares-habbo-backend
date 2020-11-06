<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Exception\GuildException;
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
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

    /**
     * @var GuildMemberRepository
     */
    private GuildMemberRepository $guildMemberRepository;

    /**
     * RoomController constructor.
     *
     * @param   GuildRepository         $guildRepository
     * @param   GuildMemberRepository   $guildMemberRepository
     */
    public function __construct(
        GuildRepository $guildRepository,
        GuildMemberRepository $guildMemberRepository
    ) {
        $this->guildRepository       = $guildRepository;
        $this->guildMemberRepository = $guildMemberRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws GuildException|DataObjectManagerException
     */
    public function guild(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->getGuild((int) $id);

        if (!$guild) {
            throw new GuildException(__('No specific Guild found'));
        }

        return $this->respond(
            $response,
            response()
                ->setData($guild)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $guilds = $this->guildRepository
            ->getPaginatedGuildList(
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($guilds)
        );
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
    public function members(Request $request, Response $response, array $args): Response
    {
        /** @var int $guildId */
        $guildId = $args['guild_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $members = $this->guildMemberRepository
            ->getPaginatedGuildMembers(
                (int) $guildId,
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($members)
        );
    }

    /**
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function mostMembers(Request $request, Response $response): Response
    {
        /** @var Guild $guild */
        $guild = $this->guildRepository->getMostMemberGuild();

        return $this->respond(
            $response,
            response()
                ->setData($guild)
        );
    }
}
