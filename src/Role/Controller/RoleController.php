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
use Ares\Role\Service\DeleteRoleService;
use Ares\Role\Service\EditRoleService;
use Ares\Role\Service\RoleTreeService;
use Ares\Role\Service\UpdateChildRoleParentService;
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
     * @param RoleTreeService $roleTreeService
     * @param RoleRepository          $roleRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        private CreateRoleService $createRoleService,
        private EditRoleService $editRoleService,
        private CreateChildRoleService $createChildRoleService,
        private AssignRankToRoleService $assignRankToRoleService,
        private ValidationService $validationService,
        private DeleteRoleService $deleteRoleService,
        private DeleteChildRoleService $deleteChildRoleService,
        private RoleTreeService $roleTreeService,
        private RoleRepository $roleRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private UpdateChildRoleParentService $updateChildRoleParentService
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
     */
    public function treeView(Request $request, Response $response): Response
    {
        //TODO Implement this in RoleTreeService
        $rootRole = $this->roleRepository->getRootRole(); //First I look up for a role that has isRoot as true

        $rootChildren = $this->roleHierarchyRepository->getChildIds([$rootRole->getId()]); //Then looking at ares_roles_hierarchy I get all role children Ids

        $rootRole->children = [];

        if(count($rootChildren) > 0) { //If role has children then...
            foreach($rootChildren as $category) { //for each child
                /** @var Role $groupRole */
                $groupRole = $this->roleRepository->get($category); //look for its corresponding role based on the Id

                $groupChildren = $this->roleHierarchyRepository->getChildIds([$category]); //Now get the child children Ids

                $groupRole->children = [];

                if(count($groupChildren) > 0) { //Repeat the same thing, if child has children
                    foreach($groupChildren as $groupChild) {  //for each child' child
                        /** @var Role $roleRank */
                        $roleRank = $this->roleRepository->get($groupChild); //get its corresponding role based on the Id
                        
                        $roleRank->getPermissionWithUsers();

                        array_push($groupRole->children, $roleRank);  //Push the role to child children        
                    }
                }

                array_push($rootRole->children, $groupRole); //Push the role to root children
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
    public function createRankRole(Request $request, Response $response): Response
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
}
