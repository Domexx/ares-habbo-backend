<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\Contract\UserCurrencyInterface;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Ares\User\Service\Currency\UpdateCurrencyService;
use Ares\User\Service\Settings\ChangeRankService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 *
 * @package Ares\User\Controller
 */
class UserController extends BaseController
{
    /**
     * UserController constructor.
     *
     * @param UserRepository    $userRepository
     * @param ValidationService $validationService
     * @param updateCurrencyService $updateCurrencyService
     * @param ChangeRankService $changeRankService
     */
    public function __construct(
        private UserRepository $userRepository,
        private ValidationService $validationService,
        private UpdateCurrencyService $updateCurrencyService,
        private ChangeRankService $changeRankService
    ) {}

    /**
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     *
     * @return Response Returns a Response with the given Data
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function user(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = user($request);
        $user->getRole();
        $user->getCurrencies();
        $user->getPermissions();

        return $this->respond($response, response()->setData($user));
    }

    /**
     * @param int $userId
     *
     * @return Response
     * @throws NoSuchEntityException
     */
    public function viewUser(Request $request, Response $response, array $args): Response
    {
        $userId = $args['id'];

        $searchCriteria = $this->userRepository
            ->getDataObjectManager() 
            ->where('id', $userId)
            ->addRelation('currencies')
            ->addRelation('hidden');

        /** @var User $user */
        $user = $this->userRepository->getOneBy($searchCriteria, true, false);

        return $this->respond($response, response()->setData($user));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function getLook(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required',
        ]);

        $userLook = $this->userRepository->getUserLook($parsedData['username']);

        return $this->respond(
            $response,
            response()
                ->setData($userLook)
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
    public function allList(Request $request, Response $response, array $args): Response
    {
        //BUG for some reason these variables aren't being treated as their corresponding variable type.

        /** @var int $page */
        $page = (int) $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = (int) $args['rpp'];

        /** @var PaginatedCollection $users */
        $users = $this->userRepository
            ->getPaginatedUsersList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * Updates user currency by given data.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     * @throws ValidationException
     */
    public function changeRank(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_ID => 'required',
            UserInterface::COLUMN_RANK => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $this->changeRankService->execute($user, $parsedData);

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }

    /**
     * Gets all current Online User and counts them
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function onlineUser(Request $request, Response $response): Response
    {
        $onlineUser = $this->userRepository->getUserOnlineCount();

        return $this->respond(
            $response,
            response()
                ->setData([
                    'count' => $onlineUser
                ])
        );
    }
}
