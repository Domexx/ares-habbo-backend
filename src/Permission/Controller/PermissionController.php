<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Permission\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Permission\Repository\PermissionRepository;
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
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function listUserWithRank(Request $request, Response $response): Response
    {
        $users = $this->permissionRepository->getListOfUserWithRanks();

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }
}
