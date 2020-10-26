<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Service\CreateRconCommandService;
use Ares\Rcon\Service\DeleteRconCommandService;
use Ares\Rcon\Service\ExecuteRconCommandService;
use Ares\Role\Exception\RoleException;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RconController
 *
 * @package Ares\Rcon\Controller
 */
class RconController extends BaseController
{
    /**
     * @var ExecuteRconCommandService
     */
    private ExecuteRconCommandService $executeRconCommandService;

    /**
     * @var DeleteRconCommandService
     */
    private DeleteRconCommandService $deleteRconCommandService;

    /**
     * @var CreateRconCommandService
     */
    private CreateRconCommandService $createRconCommandService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * RconController constructor.
     *
     * @param ExecuteRconCommandService $executeRconCommandService
     * @param DeleteRconCommandService  $deleteRconCommandService
     * @param CreateRconCommandService  $createRconCommandService
     * @param UserRepository            $userRepository
     * @param ValidationService         $validationService
     */
    public function __construct(
        ExecuteRconCommandService $executeRconCommandService,
        DeleteRconCommandService $deleteRconCommandService,
        CreateRconCommandService $createRconCommandService,
        UserRepository $userRepository,
        ValidationService $validationService
    ) {
        $this->executeRconCommandService = $executeRconCommandService;
        $this->deleteRconCommandService = $deleteRconCommandService;
        $this->createRconCommandService = $createRconCommandService;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws JsonException
     * @throws RconException
     * @throws RoleException
     * @throws UserException
     * @throws ValidationException
     * @throws DataObjectManagerException
     */
    public function executeCommand(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'command' => 'required'
        ]);

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request);

        $customResponse = $this->executeRconCommandService
            ->execute(
                $user->getId(),
                $parsedData
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
