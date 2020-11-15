<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Profile\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\Photo\Repository\PhotoRepository;
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
        private UserRepository $userRepository,
        private RoomRepository $roomRepository,
        private GuildMemberRepository $guildMemberRepository,
        private MessengerRepository $messengerRepository,
        private PhotoRepository $photoRepository,
        private UserBadgeRepository $userBadgeRepository
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws NoSuchEntityException
     */
    public function slotBadges(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['profile_id'];

        /** @var User $profile */
        $profile = $this->userRepository->get($profileId);

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
     * @throws NoSuchEntityException
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
        $profile = $this->userRepository->get($profileId);

        $badges = $this->userBadgeRepository
            ->getPaginatedBadgeList(
                $profile->getId(),
                $page,
                $resultPerPage
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
     * @throws NoSuchEntityException
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
        $profile = $this->userRepository->get($profileId);

        $friends = $this->messengerRepository
            ->getPaginatedMessengerFriends(
                $profile->getId(),
                $page,
                $resultPerPage
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
     * @throws NoSuchEntityException
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
        $profile = $this->userRepository->get($profileId);

        $rooms = $this->roomRepository
            ->getUserRoomsPaginatedList(
                $profile->getId(),
                $page,
                $resultPerPage
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
     * @throws NoSuchEntityException
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
        $profile = $this->userRepository->get($profileId);

        $guilds = $this->guildMemberRepository
            ->getPaginatedProfileGuilds(
                $profile->getId(),
                $page,
                $resultPerPage
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
     * @throws NoSuchEntityException
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
        $profile = $this->userRepository->get($profileId);

        $photos = $this->photoRepository
            ->getPaginatedUserPhotoList(
                $profile->getId(),
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($photos)
        );
    }
}
