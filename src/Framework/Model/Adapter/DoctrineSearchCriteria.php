<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Model\Adapter;

use Ares\Framework\Model\SearchCriteria;

/**
 * Class DoctrineSearchCriteria
 *
 * @package Ares\Framework\Model\Adapter
 */
class DoctrineSearchCriteria extends SearchCriteria
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        $result = [];
        $filters = parent::getFilters();

        foreach ($filters as $filter) {
            if ($filter['operator'] !== '=') {
                continue;
            }

            $result[$filter['field']] = $filter['value'];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getOrders(): array
    {
        $result = [];
        $orders = parent::getOrders();

        foreach ($orders as $order) {
            $result[$order['field']] = $order['direction'];
        }

        return $result;
    }
}
