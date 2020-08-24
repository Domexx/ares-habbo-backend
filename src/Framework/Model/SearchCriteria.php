<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model;

use Ares\Framework\Interfaces\SearchCriteriaInterface;

/**
 * Class SearchCriteria
 *
 * @package Ares\Framework\Model
 */
class SearchCriteria implements SearchCriteriaInterface
{
    /**
     * @var string
     */
    private string $searchTerm = '';

    /**
     * @var string
     */
    private string $entity = '';

    /**
     * @var array
     */
    private array $filters = [];

    /**
     * @var array
     */
    private array $orders = [];

    /**
     * @var array
     */
    private array $fields = [];

    /**
     * @var int
     */
    private int $page = 0;

    /**
     * @var int
     */
    private int $limit = 0;

    /**
     * @var int
     */
    private int $offset = 0;

    /**
     * @param string $searchTerm
     *
     * @return SearchCriteria
     */
    public function setSearchTerm(string $searchTerm): SearchCriteriaInterface
    {
        $this->searchTerm = $searchTerm;

        return $this;
    }

    /**
     * @param string $entity
     *
     * @return SearchCriteria
     */
    public function setEntity(string $entity): SearchCriteriaInterface
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param        $value
     *
     * @return $this
     */
    public function addFilter(string $field, $value, string $operator = self::ORDER_DIRECTION_EQUALS): SearchCriteriaInterface
    {
        $this->filters[] = [
            self::FILTER_FIELD_KEY => $field,
            self::FILTER_OPERATOR_KEY => $operator,
            self::FILTER_VALUE_KEY => $value
        ];

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function addOrder(string $field, string $direction): SearchCriteriaInterface
    {
        $this->orders[] = [
            self::ORDER_FIELD_KEY => $field,
            self::ORDER_DIRECTION_KEY => $direction
        ];

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields): SearchCriteriaInterface
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param int $page
     *
     * @return SearchCriteria
     */
    public function setPage(int $page): SearchCriteriaInterface
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return SearchCriteria
     */
    public function setLimit(int $limit): SearchCriteriaInterface
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return SearchCriteria
     */
    public function setOffset(int $offset): SearchCriteriaInterface
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        return $this->searchTerm;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getCacheKey(): string
    {
        $filters = $this->filters;
        $filterSlug = '';

        foreach ($filters as $filter) {
            if (!$filter) {
               continue;
            }
            $filterSlug .= $filter['field'].$filter['value'].$filter['operator'];
        }

        $orders = $this->orders;
        $orderSlug = '';

        foreach ($orders as $order) {
            if (!$order) {
                continue;
            }
            $orderSlug .= $order['field'].$order['direction'];
        }

        $fields = $this->fields;
        $fields = implode('', $fields);

        $criteria = [
            $this->searchTerm,
            $this->entity,
            $this->page,
            $this->limit,
            $this->offset,
            $filterSlug,
            $orderSlug,
            $fields
        ];

        $criteria = implode(',', $criteria);

        if (!$result = json_encode($criteria)) {
            return '';
        }

        return $result;
    }
}
