<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Gift;

use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\Gift\DailyGiftInterface;

/**
 * Class DailyGift
 *
 * @package Ares\User\Entity\Gift
 */
class DailyGift extends DataObject implements DailyGiftInterface
{
    /** @var string */
    public const TABLE = 'ares_gifts';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(DailyGiftInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return DailyGift
     */
    public function setId(int $id): DailyGift
    {
        return $this->setData(DailyGiftInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(DailyGiftInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return DailyGift
     */
    public function setUserId(int $user_id): DailyGift
    {
        return $this->setData(DailyGiftInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->getData(DailyGiftInterface::COLUMN_AMOUNT);
    }

    /**
     * @param int $amount
     *
     * @return DailyGift
     */
    public function setAmount(int $amount): DailyGift
    {
        return $this->setData(DailyGiftInterface::COLUMN_AMOUNT, $amount);
    }

    /**
     * @return int
     */
    public function getPickTime(): int
    {
        return $this->getData(DailyGiftInterface::COLUMN_PICK_TIME);
    }

    /**
     * @param int $pick_time
     *
     * @return DailyGift
     */
    public function setPickTime(int $pick_time): DailyGift
    {
        return $this->setData(DailyGiftInterface::COLUMN_PICK_TIME, $pick_time);
    }
}
