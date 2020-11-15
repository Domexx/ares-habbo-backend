<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Payment\Entity\Payment;

/**
 * Class PaymentRepository
 *
 * @package Ares\Payment\Repository
 */
class PaymentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_PAYMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_PAYMENT_COLLECTION_';

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
     * @param int|null $userId
     *
     * @return Payment|null
     */
    public function getExistingPayment(?int $userId): ?Payment
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'processed' => 0
            ]);

        return $this->getList($searchCriteria)->first();
    }
}
