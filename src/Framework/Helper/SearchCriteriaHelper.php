<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Helper;

use Ares\Framework\Exception\SearchCriteriaException;
use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Model\SearchCriteria;
use ErrorException;

/**
 * Class SearchCriteriaHelper
 *
 * @package Ares\Framework\Helper
 */
class SearchCriteriaHelper
{
    /**
     * Takes query and builds a SearchCriteria out of it.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param array                   $request
     *
     * @return SearchCriteria
     * @throws SearchCriteriaException
     */
    public function getSearchCriteriaByRequest(SearchCriteriaInterface $searchCriteria, array $request): SearchCriteriaInterface
    {
        try {
            $term = $request['term'] ?: '';
            $page = $request['page'] ?: 0;
            $limit = $request['limit'] ?: 0;
            $offset = $request['offset'] ?: 0;

            $searchCriteria->setSearchTerm($term);
            $searchCriteria->setPage($page);
            $searchCriteria->setLimit($limit);
            $searchCriteria->setOffset($offset);

            if (!is_array($filters = $request['filters'])) {
                $filters = [];
            }

            if (!is_array($orders = $request['orders'])) {
                $orders = [];
            }

            foreach ($filters as $filter) {
                $searchCriteria->addFilter(
                    $filter['field'],
                    $filter['operator'],
                    $filter['value']
                );
            }

            foreach ($orders as $order) {
                $searchCriteria->addOrder(
                    $order['field'],
                    $order['direction']
                );
            }
        } catch (ErrorException $errorException) {
            throw new SearchCriteriaException(
                'Seems that the structure of the query is wrong or some filter/order sub-fields are missing.',
                $errorException->getCode()
            );
        }

        return $searchCriteria;
    }
}
