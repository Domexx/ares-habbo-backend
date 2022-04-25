<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;
use Ares\User\Service\UserHallOfFame\FetchTopsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserHallOfFameController
 *
 * @package Ares\User\Controller
 */
class UserHallOfFameController extends BaseController
{
    /**
     * UserHallOfFameController constructor.
     *
     * @param UserRepository         $userRepository
     * @param UserSettingRepository  $userSettingRepository
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        private UserRepository $userRepository,
        private UserSettingRepository $userSettingRepository,
        private UserCurrencyRepository $userCurrencyRepository,
        private FetchTopsService $fetchTopsService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function tops(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $type = $args['type'];

        $customResponse = $this->fetchTopsService->execute($type);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function topAchievement(Request $request, Response $response): Response
    {
        $users = $this->userSettingRepository->getTopAchievements();

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function topOnlineTime(Request $request, Response $response): Response
    {
        $users = $this->userSettingRepository->getTopOnlineTime();

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }
}
