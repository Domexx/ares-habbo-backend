<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Service\CreateRconCommandService;
use Ares\Rcon\Service\DeleteRconCommandService;
use Ares\Rcon\Service\ExecuteRconCommandService;
use Ares\Role\Exception\RoleException;
use Ares\User\Entity\User;
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
     * RconController constructor.
     *
     * @param ExecuteRconCommandService $executeRconCommandService
     * @param DeleteRconCommandService  $deleteRconCommandService
     * @param CreateRconCommandService  $createRconCommandService
     * @param ValidationService         $validationService
     */
    public function __construct(
        private ExecuteRconCommandService $executeRconCommandService,
        private DeleteRconCommandService $deleteRconCommandService,
        private CreateRconCommandService $createRconCommandService,
        private ValidationService $validationService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws JsonException
     * @throws RconException
     * @throws RoleException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function executeCommand(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'command' => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

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
