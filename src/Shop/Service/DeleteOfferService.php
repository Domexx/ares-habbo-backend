<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Service;

use Ares\Shop\Exception\OfferException;
use Ares\Shop\Interfaces\Response\OfferResponseCodeInterface;
use Ares\Shop\Repository\OfferRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;

/**
 * Class DeleteOfferService
 *
 * @package Ares\Shop\Service
 */
class DeleteOfferService
{
    /**
     * DeleteOfferService constructor.
     *
     * @param OfferRepository $offerRepository
     */
    public function __construct(
        private OfferRepository $offerRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws OfferException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        $deleted = $this->offerRepository->delete($id);

        if (!$deleted) {
            throw new OfferException(
                __('Offer could not be deleted'),
                OfferResponseCodeInterface::RESPONSE_OFFER_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
