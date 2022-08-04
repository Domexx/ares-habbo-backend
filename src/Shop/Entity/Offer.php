<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Framework\Model\Query\Collection;
use Ares\Shop\Entity\Contract\OfferInterface;
use Ares\Payment\Repository\PaymentRepository;

/**
 * Class Offer
 *
 * @package Ares\Shop\Entity
 */
class Offer extends DataObject implements OfferInterface
{
    /** @var string */
    public const TABLE = 'ares_shop_offers';

    /** @var array **/
    public const RELATIONS = [
        'payments' => 'getPayments'
    ];

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

    /**
     * @return Collection|null
    */
    public function getPayments(): Collection|null
    {
        $payments = $this->getData('payments');

        if($payments) {
            return $payments;
        }

        if(!isset($this)) {
            return null;
        }

        /** @var PaymentRepository $paymentRepository */
        $paymentRepository = repository(PaymentRepository::class);

        /** @var Collection $payments */
        $payments = $paymentRepository->getOfferPayments($this->getId());

        $this->setPayments($payments);

        return $payments;
    }

    /**
     * @param Collection $payments
     *
     * @return Offer
    */
    public function setPayments(mixed $payments): Offer
    {
        return $this->setData('payments', $payments);
    }
}
