<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

use Ares\Framework\Model\SearchCriteria;

/**
 * Interface RepositoryInterface
 *
 * @package Ares\Framework\Interfaces
 */
interface RepositoryInterface
{
    /**
     * Get object by id.
     *
     * @param int  $id
     * @param bool $cachedEntity
     *
     * @return object|null
     */
    public function get(int $id, bool $cachedEntity = true);

    /**
     * @param SearchCriteria $searchCriteria
     *
     * @param bool           $cachedEntity
     *
     * @return mixed
     */
    public function getList(SearchCriteria $searchCriteria, bool $cachedEntity = true);

    /**
     * Save object.
     *
     * @param object $model
     * @return object
     */
    public function save(object $model): object;

    /**
     * Updates the Object
     *
     * @param object $model
     * @return object
     */
    public function update(object $model): object;

    /**
     * @param SearchCriteria $searchCriteria
     *
     * @param bool           $cachedEntity
     *
     * @return mixed
     */
    public function paginate(SearchCriteria $searchCriteria, bool $cachedEntity = true);

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
