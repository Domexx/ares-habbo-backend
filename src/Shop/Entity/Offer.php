<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Shop\Entity\Contract\OfferInterface;

/**
 * Class Offer
 *
 * @package Ares\Shop\Entity
 */
class Offer extends DataObject implements OfferInterface
{
    /** @var string */
    public const TABLE = 'ares_shop_offers';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(OfferInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Offer
     */
    public function setId(int $id): Offer
    {
        return $this->setData(OfferInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(OfferInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Offer
     */
    public function setTitle(string $title): Offer
    {
        return $this->setData(OfferInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(OfferInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription(string $description): Offer
    {
        return $this->setData(OfferInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(OfferInterface::COLUMN_IMAGE);
    }

    /**
     * @param string $image
     *
     * @return Offer
     */
    public function setImage(string $image): Offer
    {
        return $this->setData(OfferInterface::COLUMN_IMAGE, $image);
    }

    /**
     * @return string
     */
    public function getOfferData(): string
    {
        return $this->getData(OfferInterface::COLUMN_DATA);
    }

    /**
     * @param string $offerData
     *
     * @return Offer
     */
    public function setOfferData(string $offerData): Offer
    {
        return $this->setData(OfferInterface::COLUMN_DATA, $offerData);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->getData(OfferInterface::COLUMN_PRICE);
    }

    /**
     * @param float $price
     *
     * @return Offer
     */
    public function setPrice(float $price): Offer
    {
        return $this->setData(OfferInterface::COLUMN_PRICE, $price);
    }
}
