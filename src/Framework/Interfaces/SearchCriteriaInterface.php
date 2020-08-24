<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

/**
 * Interface SearchCriteriaInterface
 *
 * @package Ares\Framework\Interfaces
 */
interface SearchCriteriaInterface
{
    /**
     * @var string
     */
    public const FILTER_FIELD_KEY = 'field';

    /**
     * @var string
     */
    public const FILTER_VALUE_KEY = 'value';

    /**
     * @var string
     */
    public const FILTER_OPERATOR_KEY = 'operator';

    /**
     * @var string
     */
    public const ORDER_FIELD_KEY = 'field';

    /**
     * @var string
     */
    public const ORDER_DIRECTION_KEY = 'direction';

    /**
     * @var string
     */
    public const ORDER_DIRECTION_EQUALS = '=';

    /**
     * @param string $searchTerm
     *
     * @return mixed
     */
    public function setSearchTerm(string $searchTerm): SearchCriteriaInterface;

    /**
     * @param string $entity
     *
     * @return mixed
     */
    public function setEntity(string $entity): SearchCriteriaInterface;

    /**
     * @param string $field
     * @param mixed  $value
     * @param string $operator
     *
     * @return mixed
     */
    public function addFilter(string $field, $value, string $operator = '='): SearchCriteriaInterface;

    /**
     * @param string $field
     * @param string $direction
     *
     * @return mixed
     */
    public function addOrder(string $field, string $direction): SearchCriteriaInterface;

    /**
     * @param array $fields
     *
     * @return mixed
     */
    public function setFields(array $fields): SearchCriteriaInterface;

    /**
     * @param int $page
     *
     * @return mixed
     */
    public function setPage(int $page): SearchCriteriaInterface;

    /**
     * @param int $limit
     *
     * @return mixed
     */
    public function setLimit(int $limit): SearchCriteriaInterface;

    /**
     * @param int $offset
     *
     * @return mixed
     */
    public function setOffset(int $offset): SearchCriteriaInterface;

    /**
     * @return mixed
     */
    public function getSearchTerm(): string;

    /**
     * @return string
     */
    public function getEntity(): string;

    /**
     * @return array
     */
    public function getFilters(): array;

    /**
     * @return array
     */
    public function getOrders(): array;

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     * @return int
     */
    public function getPage(): int;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return string
     */
    public function getCacheKey(): string;
}
