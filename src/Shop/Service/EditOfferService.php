<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Shop\Entity\Contract\OfferInterface;
use Ares\Shop\Entity\Offer;
use Ares\Shop\Exception\OfferException;
use Ares\Shop\Interfaces\Response\OfferResponseCodeInterface;
use Ares\Shop\Repository\OfferRepository;

/**
 * Class EditOfferService
 *
 * @package Ares\Shop\Service
 */
class EditOfferService
{
    /**
     * EditOfferService constructor.
     *
     * @param OfferRepository $offerRepository
     */
    public function __construct(
        private OfferRepository $offerRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws OfferException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $offerId */
        $offerId = $data['id'];

        /** @var Offer $offer */
        $offer = $this->offerRepository->get($offerId, OfferInterface::COLUMN_ID, false, false);

        $offer = $this->getEditedOffer($offer, $data);

        /** @var Offer $offer */
        $offer = $this->offerRepository->save($offer);

        return response()
            ->setData($offer);
    }

    /**
     * Returns edited offer.
     *
     * @param Offer $offer
     * @param array $data
     *
     * @return Offer
     */
    private function getEditedOffer(Offer $offer, array $data): Offer
    {
        return $offer
            ->setTitle($data['title'] ?: $offer->getTitle())
            ->setDescription($data['description'] ?: $offer->getDescription())
            ->setPrice($data['price'] ?: $offer->getPrice())
            ->setImage($data['image'] ?: $offer->getImage())
            ->setOfferData($data['data'] ?: $offer->getOfferData());
    }
}