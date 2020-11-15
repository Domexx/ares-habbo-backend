<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Service\AssignUserToRoleService;
use Ares\Role\Service\CreateChildRoleService;
use Ares\Role\Service\CreateRoleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoleController
 *
 * @package Ares\Role\Controller
 */
class RoleController extends BaseController
{
    /**
     * RoleController constructor.
     *
     * @param CreateRoleService       $createRoleService
     * @param CreateChildRoleService  $createChildRoleService
     * @param AssignUserToRoleService $assignUserToRoleService
     * @param ValidationService       $validationService
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private CreateRoleService $createRoleService,
        private CreateChildRoleService $createChildRoleService,
        private AssignUserToRoleService $assignUserToRoleService,
        private ValidationService $validationService,
        private RoleRepository $roleRepository
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

        $roles = $this->roleRepository
            ->getPaginatedRoles(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($roles)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationException
     */
    public function createRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name' => 'required'
        ]);

        $customResponse = $this->createRoleService->execute($parsedData);

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
     * @throws RoleException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function createChildRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'parent_role_id' => 'numeric|required',
            'child_role_id' => 'numeric|required'
        ]);

        $customResponse = $this->createChildRoleService->execute($parsedData);

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
    public function assignRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'user_id' => 'numeric|required',
            'role_id' => 'numeric|required'
        ]);

        $customResponse = $this->assignUserToRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function deleteRole(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->roleRepository->delete($id);

        if (!$deleted) {
            throw new RoleException(__('Role could not be deleted'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
