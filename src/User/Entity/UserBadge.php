<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
    public const TABLE = 'user_badges';

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
     * @param int $user_id
     *
     * @return UserBadge
     */
    public function setUserId(int $user_id): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getSlotId(): int
    {
        return $this->getData(UserBadgeInterface::COLUMN_SLOT_ID);
    }

    /**
     * @param int $slot_id
     *
     * @return UserBadge
     */
    public function setSlotId(int $slot_id): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_SLOT_ID, $slot_id);
    }

    /**
     * @return string
     */
    public function getBadgeCode(): string
    {
        return $this->getData(UserBadgeInterface::COLUMN_BADGE_CODE);
    }

    /**
     * @param string $badge_code
     *
     * @return UserBadge
     */
    public function setBadgeCode(string $badge_code): UserBadge
    {
        return $this->setData(UserBadgeInterface::COLUMN_BADGE_CODE, $badge_code);
    }
}
