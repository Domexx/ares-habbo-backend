<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Messenger\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Messenger\Entity\Contract\MessengerFriendshipInterface;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class MessengerFriendship
 *
 * @package Ares\Messenger\Entity
 */
class MessengerFriendship extends DataObject implements MessengerFriendshipInterface
{
    /** @var string */
    public const TABLE = 'messenger_friendships';

    /** @var array */
    public const RELATIONS = [
        'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return MessengerFriendship
     */
    public function setId(int $id): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserOneId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_USER_ONE_ID);
    }

    /**
     * @param int $userOneId
     *
     * @return MessengerFriendship
     */
    public function setUserOneId(int $userOneId): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_USER_ONE_ID, $userOneId);
    }

    /**
     * @return int
     */
    public function getUserTwoId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_USER_TWO_ID);
    }

    /**
     * @param int $userTwoId
     *
     * @return MessengerFriendship
     */
    public function setUserTwoId(int $userTwoId): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_USER_TWO_ID, $userTwoId);
    }

    /**
     * @return int
     */
    public function getRelation(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_RELATION);
    }

    /**
     * @param int $relation
     *
     * @return MessengerFriendship
     */
    public function setRelation(int $relation): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_RELATION, $relation);
    }

    /**
     * @return int
     */
    public function getFriendsSince(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_FRIENDS_SINCE);
    }

    /**
     * @param int $friendsSince
     *
     * @return MessengerFriendship
     */
    public function setFriendsSince(int $friendsSince): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_FRIENDS_SINCE, $friendsSince);
    }

    /**
     * @return User|null
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var MessengerRepository $messengerRepository */
        $messengerRepository = repository(MessengerRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $messengerRepository->getOneToOne(
            $userRepository,
            $this->getUserTwoId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    public function setUser(User $user): MessengerFriendship
    {
        return $this->setData('user', $user);
    }
}
