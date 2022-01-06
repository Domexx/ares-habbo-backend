<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Permission\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Service\ValidationService;
use Ares\Permission\Repository\PermissionRepository;
use Ares\Permission\Entity\Contract\PermissionInterface;
use Ares\Permission\Service\CreateRankService;
use Ares\Permission\Service\EditRankService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PermissionController
 *
 * @package Ares\Permission\Controller
 */
class PermissionController extends BaseController
{
    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository   $permissionRepository
     */
    public function __construct(
        private CreateRankService $createRankService,
        private EditRankService $editRankService,
        private ValidationService $validationService,
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function listRanks(Request $request, Response $response): Response
    {
        $permissions = $this->permissionRepository->getListOfPermissions();

        return $this->respond(
            $response,
            response()
                ->setData($permissions)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function rankById(Request $request, Response $response, array $args): Response
    {
        /** @var int $roleId */
        $rankId = $args['id'];

        //Role Category Information
        $rank = $this->permissionRepository->getPermissionById($rankId);

        return $this->respond(
            $response,
            response()
                ->setData($rank)
        );
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * 
     * @return Response
     * @throws DataObjectManagerException
     */
    public function listColumns(Request $request, Response $response) : Response 
    {
        $list = array_slice($this->permissionRepository->getListOfColumns(), 8);

        return $this->respond($response, response()->setData($list));
    }

    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return Response
     */
    public function createRank(Request $request, Response $response) : Response
    {

        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PermissionInterface::COLUMN_RANK_NAME => 'required',
            PermissionInterface::COLUMN_PREFIX => '',
            PermissionInterface::COLUMN_PREFIX_COLOR => '',
            PermissionInterface::COLUMN_BADGE => ''
        ]);

        $customResponse = $this->createRankService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return Response
     */
    public function editRank(Request $request, Response $response) : Response
    {

        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PermissionInterface::COLUMN_RANK_NAME => 'required',
            PermissionInterface::COLUMN_PREFIX => '',
            PermissionInterface::COLUMN_PREFIX_COLOR => '',
            PermissionInterface::COLUMN_BADGE => ''
        ]);

        $customResponse = $this->editRankService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }
}
