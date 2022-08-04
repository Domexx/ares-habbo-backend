<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Payment\Entity\Contract\PaymentInterface;
use Ares\Payment\Entity\Payment;

/**
 * Class PaymentRepository
 *
 * @package Ares\Payment\Repository
 */
class PaymentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_SHOP_PAYMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_SHOP_PAYMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Payment::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedPayments(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getUserPaginatedPayments(int $page, int $resultPerPage, int $userId): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where(PaymentInterface::COLUMN_USER_ID, $userId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return Collection
     * @throws DataObjectManagerException
     */
    public function getOfferPayments(int $offerId): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where(PaymentInterface::COLUMN_OFFER_ID, $offerId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getOfferPaginatedPayments(int $page, int $resultPerPage, int $offerId): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where(PaymentInterface::COLUMN_OFFER_ID, $offerId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int|null $userId
     *
     * @return Payment|null
     * @throws NoSuchEntityException
     */
    public function getExistingPayment(?string $orderId): ?Payment
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'order_id' => $orderId,
                'status' => 'COMPLETED'
            ]);

        return $this->getOneBy($searchCriteria, true);
    }
}
