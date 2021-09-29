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
use Ares\Role\Entity\Contract\RoleHierarchyInterface;
use Ares\Role\Entity\Contract\RoleInterface;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Service\AssignRankToRoleService;
use Ares\Role\Service\CreateChildRoleService;
use Ares\Role\Service\CreateRoleService;
use Ares\Role\Service\DeleteRoleService;
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
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private CreateRoleService $createRoleService,
        private CreateChildRoleService $createChildRoleService,
        private AssignRankToRoleService $assignRankToRoleService,
        private ValidationService $validationService,
        private DeleteRoleService $deleteRoleService,
        private RoleRepository $roleRepository,
        private RoleHierarchyRepository $roleHierarchyRepository
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
        $rootRole = $this->roleRepository->getRootRole(); //First I look up for a role that has isRoot as true

        $rootChildren = $this->roleHierarchyRepository->getChildIds([$rootRole->getId()]); //Then looking at ares_roles_hierarchy I get all role children Ids

        $rootRole->children = [];

        if(count($rootChildren) > 0) { //If role has children then...
            foreach($rootChildren as $rootChild) { //for each child
                $groupRole = $this->roleRepository->getRoleById($rootChild); //look for its corresponding role based on the Id
                
                $groupChildren = $this->roleHierarchyRepository->getChildIds([$rootChild]); //Now get the child children Ids

                $groupRole->children = [];

                if(count($groupChildren) > 0) { //Repeat the same thing, if child has children
                    foreach($groupChildren as $groupChild) {  //for each child' child
                        $roleRank = $this->roleRepository->getRoleById($groupChild); //get its corresponding role based on the Id
                        $roleRank->getPermission(); //Now MERGE its rank
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
    public function roleCategories(Request $request, Response $response): Response
    {
        //First I look up for a role that has isRoot as true
        $rootRole = $this->roleRepository->getRootRole();

        //Then looking at ares_roles_hierarchy I get all role children Ids
        $rootChildren = $this->roleHierarchyRepository->getChildIds([$rootRole->getId()]);

        $rootRole->children = [];

        //If root has children (categories) then...
        if(count($rootChildren) > 0) {
            foreach($rootChildren as $rootChild) { //for each child
                $groupRole = $this->roleRepository->getRoleById($rootChild); //look for its corresponding role based on the Id

                array_push($rootRole->children, $groupRole); //Push the role to root children
            }
        }

        return $this->respond($response, response()->setData($rootRole->children));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function categoryById(Request $request, Response $response, array $args): Response
    {
        /** @var int $roleId */
        $roleId = $args['id'];

        //Role Category Information
        $role = $this->roleRepository->getRoleById($roleId);

        //TODO THROW ERROR IF ROLE IS NOT A ROOT CHILD (MEANING THIS IS NOT A CATEGORY)

        //Get Role Category Children Ids
        $roleChildren = $this->roleHierarchyRepository->getChildIds([$roleId]);

        //Set Role Category Children
        $role->children = [];

        if(count($roleChildren) > 0) { //If role has children then...
            foreach($roleChildren as $roleChild) { //for each child
                //Role Rank Information
                $roleRank = $this->roleRepository->getRoleById($roleChild);
                $roleRank->getPermission();
                array_push($role->children, $roleRank);
            }
        }

        return $this->respond($response, response()->setData($role));
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

        //Role Category Information
        $role = $this->roleRepository->getRoleById($roleId);

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
    public function assignRole(Request $request, Response $response): Response
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
}
