<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\User\Entity\User;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\Contract\UserInterface;

/**
 * Class UserRepository
 *
 * @package Ares\User\Repository
 */
class UserRepository extends BaseRepository
{
    /** @var string */
    protected string $entity = User::class;

    /** @var string */
    protected string $cachePrefix = 'ARES_USER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_COLLECTION_';

    /**
     * @return int
     */
    public function getUserOnlineCount(): int
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('online', '1');

        return $this->getList($searchCriteria, false)->count();
    }

    /**
     * @param array $exceptRanks
     * 
     * @return Collection
     */
    public function getTopCredits(array $exceptRanks = []): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->whereNotIn('rank', $exceptRanks)
            ->orderBy('credits', 'DESC')
            ->limit(10);

        return $this->getList($searchCriteria);
    }

    /**
     * @param string|null $username
     * @param string|null $mail
     *
     * @return User|null
     * @throws NoSuchEntityException
     */
    public function getRegisteredUser(?string $username, ?string $mail): ?User
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('username', $username)
            ->orWhere('mail', $mail);

        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * @param string $username
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getUserLook(string $username): ?string
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('username', $username);

        return $this->getOneBy($searchCriteria)?->getLook();
    }

    /**
     * @param string $ipRegister
     *
     * @return int
     */
    public function getAccountCountByIp(string $ipRegister): int
    {
        return $this->getDataObjectManager()
            ->where('ip_register', $ipRegister)
            ->count();
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedUsersList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                UserInterface::COLUMN_ID,
                UserInterface::COLUMN_USERNAME,
                UserInterface::COLUMN_LAST_LOGIN,
                UserInterface::COLUMN_LAST_ONLINE,
                UserInterface::COLUMN_LOOK,
                UserInterface::COLUMN_RANK,
                UserInterface::COLUMN_CREATED_AT
            ]);

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
