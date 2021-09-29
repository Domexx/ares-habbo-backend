<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Entity\Contract\PermissionInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Service\CreateRolePermissionService;
use Ares\Role\Service\CreatePermissionService;
use Ares\Role\Service\DeleteRolePermissionService;
use Ares\Role\Service\FetchUserPermissionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RolePermissionController
 *
 * @package Ares\Role\Controller
 */
class RolePermissionController extends BaseController
{
    /**
     * RolePermissionController constructor.
     *
     * @param PermissionRepository        $permissionRepository
     * @param CreatePermissionService     $createPermissionService
     * @param CreateRolePermissionService $createRolePermissionService
     * @param FetchUserPermissionService  $fetchUserPermissionService
     * @param ValidationService           $validationService
     * @param DeleteRolePermissionService $deleteRolePermissionService
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private CreatePermissionService $createPermissionService,
        private CreateRolePermissionService $createRolePermissionService,
        private FetchUserPermissionService $fetchUserPermissionService,
        private ValidationService $validationService,
        private DeleteRolePermissionService $deleteRolePermissionService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        $permissions = $this->permissionRepository->getPermissionList();

        return $this->respond($response, response()->setData($permissions));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws NoSuchEntityException
     */
    public function userPermissions(Request $request, Response $response): Response
    {
        /** @var int $userRank */
        $userRank = user($request)->getRank();

        $customResponse = $this->fetchUserPermissionService->execute($userRank);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws RoleException
     * @throws ValidationException
     */
    public function createPermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PermissionInterface::COLUMN_NAME => 'required'
        ]);

        $customResponse = $this->createPermissionService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws RoleException
     * @throws ValidationException
     */
    public function createRolePermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PermissionInterface::COLUMN_NAME => 'required'
        ]);

        $customResponse = $this->createRolePermissionService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function deleteRolePermission(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteRolePermissionService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
