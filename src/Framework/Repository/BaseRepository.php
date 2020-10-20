<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository;

use Ares\Framework\Exception\CacheException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Factory\DataObjectManagerFactory;
use Ares\Framework\Model\DataObject;
use Ares\Framework\Model\Query\DataObjectManager;
use Ares\Framework\Service\CacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class BaseRepository
 *
 * @package Ares\Framework\Repository
 */
abstract class BaseRepository
{
    /** @var string */
    private const COLUMN_ID = 'id';

    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var string
     */
    protected string $cachePrefix;

    /**
     * @var string
     */
    protected string $cacheCollectionPrefix;

    /**
     * @var DataObjectManagerFactory
     */
    protected DataObjectManagerFactory $dataObjectManagerFactory;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * BaseRepository constructor.
     *
     * @param DataObjectManagerFactory $dataObjectManagerFactory
     * @param CacheService $cacheService
     */
    public function __construct(
        DataObjectManagerFactory $dataObjectManagerFactory,
        CacheService $cacheService
    ) {
        $this->dataObjectManagerFactory = $dataObjectManagerFactory;
        $this->cacheService = $cacheService;
    }

    /**
     * Get DataObject by id.
     *
     * @param int    $id
     * @param string $column
     *
     * @return DataObject|null
     * @throws CacheException
     */
    public function get(int $id, string $column = self::COLUMN_ID): ?DataObject
    {
        $entity = $this->cacheService->get($this->cachePrefix . $id);

        if ($entity) {
            return unserialize($entity);
        }

        $dataObjectManager = $this->getDataObjectManager();
        $entity = $dataObjectManager->where($column, $id)->first();

        $this->cacheService->set($this->cachePrefix . $id, serialize($entity));

        return $entity;
    }

    /**
     * Get list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param bool              $cachedList
     *
     * @return Collection
     * @throws CacheException
     */
    public function getList(DataObjectManager $dataObjectManager, bool $cachedList = true): Collection
    {
        $cacheKey = $this->getCacheKey($dataObjectManager);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection && $cachedList) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->get();

        $this->cacheService->set($this->cacheCollectionPrefix . $cacheKey, serialize($collection));

        return $collection;
    }

    /**
     * Get paginated list of data objects by build search.
     *
     * @param DataObjectManager $dataObjectManager
     * @param int $pageNumber
     * @param int $limit
     * @return LengthAwarePaginator
     * @throws CacheException
     */
    public function getPaginatedList(
        DataObjectManager $dataObjectManager,
        int $pageNumber,
        int $limit
    ): LengthAwarePaginator {
        $cacheKey = $this->getCacheKey($dataObjectManager, (string) $pageNumber, (string) $limit);

        $collection = $this->cacheService->get($this->cacheCollectionPrefix . $cacheKey);

        if ($collection) {
            return unserialize($collection);
        }

        $collection = $dataObjectManager->paginate($limit, ['*'], 'page', $pageNumber);

        $this->cacheService->set($this->cacheCollectionPrefix . $cacheKey, serialize($collection));

        return $collection;
    }

    /**
     * Saves or updates given entity.
     *
     * @param DataObject $entity
     * @return DataObject
     * @throws DataObjectManagerException
     */
    public function save(DataObject $entity): DataObject
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        $id = $entity->getData(self::COLUMN_ID);

        try {
            if ($id) {
                $dataObjectManager
                    ->where(self::COLUMN_ID, $id)
                    ->update($entity->getData());

                return $entity;
            }

            $polyMorphedId = $dataObjectManager->insertGetId($entity->getData());
            $entity->setId($polyMorphedId);

            return $entity;
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Delete by id.
     *
     * @param int $id
     * @return bool
     * @throws DataObjectManagerException
     */
    public function delete(int $id): bool
    {
        $dataObjectManager = $this->dataObjectManagerFactory->create($this->entity);

        try {
            return (bool) $dataObjectManager->delete($id);
        } catch (\Exception $exception) {
            throw new DataObjectManagerException(
                $exception->getMessage(),
                500,
                $exception
            );
        }
    }

    /**
     * Generates cache key.
     *
     * @param DataObjectManager $dataObjectManager
     *
     * @param string ...$postfix
     * @return string
     */
    protected function getCacheKey(DataObjectManager $dataObjectManager, string ...$postfix): string
    {
        $sql = $dataObjectManager->toSql();
        $bindings = $dataObjectManager->getBindings();

        $cacheKey = vsprintf(str_replace("?", "%s", $sql), $bindings) . implode($postfix);

        return hash('tiger192,3', $cacheKey);
    }

    /**
     * Returns data object manager.
     *
     * @return DataObjectManager
     */
    public function getDataObjectManager(): DataObjectManager
    {
        return $this->dataObjectManagerFactory->create($this->entity);
    }
}
