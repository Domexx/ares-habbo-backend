<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity;

use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserBadgeInterface;

/**
 * Class UserBadge
 *
 * @package Ares\User\Entity
 */
class UserBadge extends DataObject implements UserBadgeInterface
{
    /** @var string */
    public const TABLE = 'users_badges';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserBadgeInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return UserBadge
     */
    public function setId(int $id): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserBadgeInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return UserBadge
     */
    public function setUserId(int $userId): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getSlotId(): int
    {
        return $this->getData(UserBadgeInterface::COLUMN_SLOT_ID);
    }

    /**
     * @param int $slotId
     *
     * @return UserBadge
     */
    public function setSlotId(int $slotId): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_SLOT_ID, $slotId);
    }

    /**
     * @return string
     */
    public function getBadgeCode(): string
    {
        return $this->getData(UserBadgeInterface::COLUMN_BADGE_CODE);
    }

    /**
     * @param string $badgeCode
     *
     * @return UserBadge
     */
    public function setBadgeCode(string $badgeCode): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_BADGE_CODE, $badgeCode);
    }
}
