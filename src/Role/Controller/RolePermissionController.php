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
use Ares\Role\Service\CreatePermissionService;
use Ares\Role\Service\DeleteRolePermissionService;
use Ares\Role\Service\DeleteRolePermissionsService;
use Ares\Role\Service\FetchUserPermissionService;
use Ares\Role\Service\ToggleRolePermissionService;
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
     * @param FetchUserPermissionService  $fetchUserPermissionService
     * @param ValidationService           $validationService
     * @param DeleteRolePermissionService $deleteRolePermissionService
     * @param ToggleRolePermissionService $toggleRolePermissionService
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private CreatePermissionService $createPermissionService,
        private FetchUserPermissionService $fetchUserPermissionService,
        private ValidationService $validationService,
        private DeleteRolePermissionService $deleteRolePermissionService,
        private DeleteRolePermissionsService $deleteRolePermissionsService,
        private ToggleRolePermissionService $toggleRolePermissionService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getAllRolePermissions(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $permissions = $this->permissionRepository->getPaginatedPermissionList($page, $resultPerPage);

        return $this->respond($response, response()->setData($permissions));
    }

    public function getRolePermissionsList(Request $request, Response $response, array $args): Response
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
     * @param Request $request
     * @param Response $response
     * 
     * @return Response
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function toggleRolePermission(Request $request, Response $response, array $args) {
        /** @var int $id */
        $roleId = $args['id'];

        $permissionId = $args['role_permission_id'];

        $customResponse = $this->toggleRolePermissionService->execute($roleId, $permissionId);
        
        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * 
     * @return Response
     * 
     */
    public function clearRolePermissions(Request $request, Response $response, array $args) {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteRolePermissionsService->execute($id);

        return $this->respond($response, $customResponse);
    }
}