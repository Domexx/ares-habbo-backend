<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Profile\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Profile\Exception\ProfileException;
use Ares\Room\Repository\RoomRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserBadgeRepository;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ProfileController
 *
 * @package Ares\Profile\Controller
 */
class ProfileController extends BaseController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var RoomRepository
     */
    private RoomRepository $roomRepository;

    /**
     * @var GuildMemberRepository
     */
    private GuildMemberRepository $guildMemberRepository;

    /**
     * @var MessengerRepository
     */
    private MessengerRepository $messengerRepository;

    /**
     * @var PhotoRepository
     */
    private PhotoRepository $photoRepository;

    /**
     * @var UserBadgeRepository
     */
    private UserBadgeRepository $userBadgeRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository        $userRepository
     * @param RoomRepository        $roomRepository
     * @param GuildMemberRepository $guildMemberRepository
     * @param MessengerRepository   $messengerRepository
     * @param PhotoRepository       $photoRepository
     * @param UserBadgeRepository   $userBadgeRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository,
        GuildMemberRepository $guildMemberRepository,
        MessengerRepository $messengerRepository,
        PhotoRepository $photoRepository,
        UserBadgeRepository $userBadgeRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->guildMemberRepository = $guildMemberRepository;
        $this->messengerRepository = $messengerRepository;
        $this->photoRepository = $photoRepository;
        $this->userBadgeRepository = $userBadgeRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ProfileException
     */
    public function slotBadges(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $badges = $this->userBadgeRepository
            ->getListOfSlottedUserBadges(
                $profile->getId()
            );

        return $this->respond(
            $response,
            response()
                ->setData($badges)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ProfileException
     */
    public function badgeList(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $badges = $this->userBadgeRepository
            ->getPaginatedBadgeList(
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($badges)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ProfileException
     * @throws DataObjectManagerException
     */
    public function friendList(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $friends = $this->messengerRepository
            ->getPaginatedMessengerFriends(
                $profile->getId(),
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($friends)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ProfileException
     */
    public function roomList(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int)$profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $rooms = $this->roomRepository
            ->getUserRoomsPaginatedList(
                $profile->getId(),
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($rooms)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ProfileException
     */
    public function guildList(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $guilds = $this->guildMemberRepository
            ->getPaginatedProfileGuilds(
                (int) $profile->getId(),
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
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ProfileException
     */
    public function photoList(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $photos = $this->photoRepository
            ->getPaginatedUserPhotoList(
                $profile->getId(),
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($photos)
        );
    }
}
