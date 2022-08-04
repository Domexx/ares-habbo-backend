<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rank\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Service\ValidationService;
use Ares\Rank\Repository\RankRepository;
use Ares\Rank\Entity\Contract\RankInterface;
use Ares\Rank\Service\CreateRankService;
use Ares\Rank\Service\EditRankService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RankController
 *
 * @package Ares\Rank\Controller
 */
class RankController extends BaseController
{
    /**
     * RankController constructor.
     *
     * @param RankRepository   $rankRepository
     */
    public function __construct(
        private CreateRankService $createRankService,
        private EditRankService $editRankService,
        private ValidationService $validationService,
        private RankRepository $rankRepository
    ) {}

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getAllRanks(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $ranks = $this->rankRepository->getAllRanks($page, $resultPerPage);

        return $this->respond($response, response()->setData($ranks));
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getRanksList(Request $request, Response $response): Response
    {
        $ranks = $this->rankRepository->getRanksList();

        return $this->respond($response, response()->setData($ranks));
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getRankById(Request $request, Response $response, array $args): Response
    {
        /** @var int $roleId */
        $rankId = $args['id'];

        $rank = $this->rankRepository->getRankById($rankId);

        return $this->respond($response, response()->setData($rank));
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * 
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getRankColumns(Request $request, Response $response) : Response 
    {
        $list = array_slice($this->rankRepository->getListOfColumns(), 8);

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
            RankInterface::COLUMN_RANK_NAME => 'required',
            RankInterface::COLUMN_PREFIX => '',
            RankInterface::COLUMN_PREFIX_COLOR => '',
            RankInterface::COLUMN_BADGE => ''
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
            RankInterface::COLUMN_ID => 'required',
            RankInterface::COLUMN_RANK_NAME => 'required',
            RankInterface::COLUMN_PREFIX => '',
            RankInterface::COLUMN_PREFIX_COLOR => '',
            RankInterface::COLUMN_BADGE => ''
        ]);

        $customResponse = $this->editRankService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }
}
