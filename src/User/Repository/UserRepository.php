<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Model\Query\Collection;
use Ares\User\Entity\User;
use Ares\Framework\Repository\BaseRepository;

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
     * @return Collection
     */
    public function getTopCredits(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('credits', 'DESC')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @param string|null $username
     * @param string|null $mail
     *
     * @return User|null
     */
    public function getRegisteredUser(?string $username, ?string $mail): ?User
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('username', $username)
            ->orWhere('mail', $mail);

        return $this->getList($searchCriteria, false)->first();
    }
}
