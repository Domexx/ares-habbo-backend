<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Service\ValidationService;
use Ares\Shop\Entity\Contract\OfferInterface;
use Ares\Shop\Repository\OfferRepository;
use Ares\Shop\Service\CreateOfferService;
use Ares\Shop\Service\DeleteOfferService;
use Ares\Shop\Service\EditOfferService;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ShopController
 *
 * @package Ares\Shop\Controller
 */
class ShopController extends BaseController
{
    /**
     * ShopController constructor.
     *
     * @param   OfferRepository         $offerRepository
     * @param   UserRepository          $userRepository
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private OfferRepository $offerRepository,
        private UserRepository $userRepository,
        private ValidationService $validationService,
        private CreateOfferService $createOfferService,
        private EditOfferService $editOfferService,
        private DeleteOfferService $deleteOfferService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAllOffers(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $payments = $this->offerRepository->getPaginatedOfferList($page, $resultPerPage);

        return $this->respond($response, response()->setData($payments));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAvailableOffers(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $payments = $this->offerRepository->getPaginatedOfferList($page, $resultPerPage);

        return $this->respond($response, response()->setData($payments));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws NoSuchEntityException
    */
    public function getOfferById(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Payment $payment */
        $offer = $this->offerRepository->get($id);

        return $this->respond($response, response()->setData($offer));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws OfferException
     * @throws ValidationException
     */
    public function createOffer(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            OfferInterface::COLUMN_TITLE => 'required',
            OfferInterface::COLUMN_IMAGE => 'required',
            OfferInterface::COLUMN_DESCRIPTION => 'required',
            OfferInterface::COLUMN_DATA => 'required',
            OfferInterface::COLUMN_PRICE => 'required|numeric'
        ]);

        $customResponse = $this->createOfferService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request $request
     * @param Response $response
     * 
     * @return Response
    */
    public function editOffer(Request $request, Response $response) : Response
    {

        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            OfferInterface::COLUMN_TITLE => 'required',
            OfferInterface::COLUMN_IMAGE => 'required',
            OfferInterface::COLUMN_DESCRIPTION => 'required',
            OfferInterface::COLUMN_DATA => 'required',
            OfferInterface::COLUMN_PRICE => 'required|numeric'
        ]);

        $customResponse = $this->editOfferService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws OfferException
     * @throws DataObjectManagerException
     */
    public function deleteOffer(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteOfferService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
