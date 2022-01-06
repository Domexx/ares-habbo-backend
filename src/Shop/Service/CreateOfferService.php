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
use Ares\Shop\Entity\Offer;
use Ares\Shop\Exception\OfferException;
use Ares\Shop\Interfaces\Response\OfferResponseCodeInterface;
use Ares\Shop\Repository\OfferRepository;

/**
 * Class CreateOfferService
 *
 * @package Ares\Shop\Service
 */
class CreateOfferService
{
    /**
     * CreateOfferService constructor.
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
        /** @var Offer $offer */
        $offer = $this->getNewOffer($data);

        /** @var Offer $offer */
        $offer = $this->offerRepository->save($offer);

        return response()->setData($offer);
    }

    /**
     * Returns new offer.
     *
     * @param array $data
     *
     * @return Offer
     */
    private function getNewOffer(array $data): Offer
    {
        $offer = new Offer();

        return $offer
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setPrice($data['price'])
            ->setImage($data['image'])
            ->setOfferData($data['data']);
    }
}