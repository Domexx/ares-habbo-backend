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
use Ares\Framework\Exception\ValidationException as ValidationExceptionAlias;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Service\CreateRolePermissionService;
use Ares\Role\Service\CreatePermissionService;
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
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private CreatePermissionService $createPermissionService,
        private CreateRolePermissionService $createRolePermissionService,
        private FetchUserPermissionService $fetchUserPermissionService,
        private ValidationService $validationService
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
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $permissions = $this->permissionRepository
            ->getPaginatedPermissionList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($permissions)
        );
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
        /** @var int $userId */
        $userId = user($request)->getId();

        $customResponse = $this->fetchUserPermissionService->execute($userId);

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
     * @throws ValidationExceptionAlias
     */
    public function createPermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name' => 'required'
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
     * @throws ValidationExceptionAlias
     */
    public function createRolePermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name' => 'required'
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

        $deleted = $this->permissionRepository->delete($id);

        if (!$deleted) {
            throw new RoleException(__('Permission could not be deleted'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
