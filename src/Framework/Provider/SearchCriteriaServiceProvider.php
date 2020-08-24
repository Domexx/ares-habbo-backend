<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Model\SearchCriteria;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class SearchCriteriaServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class SearchCriteriaServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        SearchCriteria::class,
        DoctrineSearchCriteria::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(SearchCriteria::class, function () use ($container) {
            return new SearchCriteria();
        });

        $container->add(DoctrineSearchCriteria::class, function () use ($container) {
            return new DoctrineSearchCriteria();
        });
    }
}
