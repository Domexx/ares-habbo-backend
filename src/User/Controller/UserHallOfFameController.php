<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;
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
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * @var UserCurrencyRepository
     */
    private UserCurrencyRepository $userCurrencyRepository;

    /**
     * UserHallOfFameController constructor.
     *
     * @param UserRepository         $userRepository
     * @param UserSettingRepository  $userSettingRepository
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository,
        UserCurrencyRepository $userCurrencyRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function topCredits(Request $request, Response $response): Response
    {
        $users = $this->userRepository->getTopCredits();

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
    public function topDiamonds(Request $request, Response $response): Response
    {
        $users = $this->userCurrencyRepository->getTopDiamonds();

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
    public function topDuckets(Request $request, Response $response): Response
    {
        $users = $this->userCurrencyRepository->getTopDuckets();

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
            response()->setData($users)
        );
    }
}
