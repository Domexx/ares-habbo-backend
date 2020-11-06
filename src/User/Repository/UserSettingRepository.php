<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserSetting;

/**
 * Class UserSettingRepository
 *
 * @package Ares\User\Repository
 */
class UserSettingRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_SETTING_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = UserSetting::class;

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopAchievements(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'achievement_score'])
            ->orderBy('achievement_score', 'DESC')
            ->addRelation('user')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopOnlineTime(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'online_time'])
            ->orderBy('online_time', 'DESC')
            ->addRelation('user')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return UserSetting|null
     *
     * @throws DataObjectManagerException
     */
    public function getUserWithMostRespects(): ?UserSetting
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'respects_received'])
            ->orderBy('respects_received', 'DESC')
            ->limit(1)
            ->addRelation('user');

        return $this->getList($searchCriteria)->first();
    }
}
