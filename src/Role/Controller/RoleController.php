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
use Ares\Role\Entity\Role;
use Ares\Role\Entity\Contract\RoleHierarchyInterface;
use Ares\Role\Entity\Contract\RoleInterface;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Service\AssignRankToRoleService;
use Ares\Role\Service\CreateChildRoleService;
use Ares\Role\Service\CreateRoleService;
use Ares\Role\Service\DeleteChildRoleService;
use Ares\Role\Service\DeleteRoleRankService;
use Ares\Role\Service\DeleteRoleService;
use Ares\Role\Service\EditRoleService;
use Ares\Role\Service\RoleTreeService;
use Ares\Role\Service\UpdateChildRoleOrderService;
use Ares\Role\Service\UpdateChildRoleParentService;
use Ares\Role\Service\UpdateRoleRankService;
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
     * @param AssignRankToRoleService $assignRankToRoleService
     * @param ValidationService       $validationService
     * @param DeleteRoleService       $deleteRoleService
     * @param DeleteChildRoleService $deleteChildRoleService
     * @param DeleteRoleRankService $deleteRoleRankService
     * @param RoleTreeService $roleTreeService
     * @param RoleRepository          $roleRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     * @param UpdateChildRoleOrderService $updateChildRoleOrderService
     * @param UpdateRoleRankService $updateRoleRankService
     */
    public function __construct(
        private CreateRoleService $createRoleService,
        private EditRoleService $editRoleService,
        private CreateChildRoleService $createChildRoleService,
        private AssignRankToRoleService $assignRankToRoleService,
        private ValidationService $validationService,
        private DeleteRoleService $deleteRoleService,
        private DeleteChildRoleService $deleteChildRoleService,
        private DeleteRoleRankService $deleteRoleRankService,
        private RoleTreeService $roleTreeService,
        private RoleRepository $roleRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private UpdateChildRoleParentService $updateChildRoleParentService,
        private UpdateChildRoleOrderService $updateChildRoleOrderService,
        private UpdateRoleRankService $updateRoleRankService
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
     * TODO Implement this is RoleTreeService
     * 
     * Retrieves all Role Hierarchy Tree by setting a root Role on Database.
     * Role Tree is made up of 3 levels: Root > Categories > Normal Roles (These are attached to a Rank)
     * Normal Roles are retrieved with their corresponding Rank and Users.
     * 
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function treeView(Request $request, Response $response): Response
    {
        $rootRole = $this->roleRepository->getRootRole();

        $rootChildren = $this->roleHierarchyRepository->getChildIds([$rootRole->getId()]);

        $rootRole->children = [];

        if(count($rootChildren) > 0) {
            foreach($rootChildren as $category) {
                /** @var Role $groupRole */
                $groupRole = $this->roleRepository->get($category);

                $groupChildren = $this->roleHierarchyRepository->getChildIds([$category]);

                $groupRole->children = [];

                if(count($groupChildren) > 0) {
                    foreach($groupChildren as $groupChild) {

                        /** @var Role $roleRank */
                        $roleRank = $this->roleRepository->get($groupChild);
                        
                        $roleRank->getPermissionWithUsers();

                        array_push($groupRole->children, $roleRank);
                    }
                }

                array_push($rootRole->children, $groupRole);
            }
        }

        return $this->respond($response, response()->setData($rootRole));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function roleById(Request $request, Response $response, array $args): Response
    {
        /** @var int $roleId */
        $roleId = $args['id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        $role->getRolePermissions();
        $role->getPermission();

        return $this->respond($response, response()->setData($role));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationException|NoSuchEntityException
     */
    public function createRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleInterface::COLUMN_NAME => 'required',
            RoleInterface::COLUMN_DESCRIPTION => 'required'
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
            RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID => 'numeric|required',
            RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID => 'numeric|required'
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
    public function assignRank(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleRankInterface::COLUMN_RANK_ID => 'numeric|required',
            RoleRankInterface::COLUMN_ROLE_ID => 'numeric|required'
        ]);

        $customResponse = $this->assignRankToRoleService->execute($parsedData);

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
    public function updateRoleRank(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleRankInterface::COLUMN_RANK_ID => 'numeric|required',
            RoleRankInterface::COLUMN_ROLE_ID => 'numeric|required'
        ]);

        $customResponse = $this->updateRoleRankService->execute($parsedData);

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
     * @throws ValidationException|NoSuchEntityException
     */
    public function editRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleInterface::COLUMN_ID => 'required',
            RoleInterface::COLUMN_NAME => 'required',
            RoleInterface::COLUMN_DESCRIPTION => 'required'
        ]);

        $customResponse = $this->editRoleService->execute($parsedData);

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
    public function updateChildRoleParent(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID => 'numeric|required',
            RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID => 'numeric|required'
        ]);

        $customResponse = $this->updateChildRoleParentService->execute($parsedData);

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
    public function updateChildRoleOrder(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID => 'numeric|required',
            RoleHierarchyInterface::COLUMN_ORDER_ID => 'numeric|required'
        ]);

        $customResponse = $this->updateChildRoleOrderService->execute($parsedData);

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

        $customResponse = $this->deleteRoleService->execute($id);

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
    public function deleteChildRole(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteChildRoleService->execute($id);

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
    public function deleteRoleRank(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteRoleRankService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
