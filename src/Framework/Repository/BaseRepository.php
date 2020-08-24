<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository;

use Ares\Framework\Interfaces\RepositoryInterface;
use Ares\Framework\Service\CacheService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Jhg\DoctrinePagination\ORM\PaginatedRepository;

/**
 * Class BaseRepository
 *
 * @package Ares\Framework\Repository
 */
abstract class BaseRepository extends PaginatedRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * BaseRepository constructor.
     *
     * @param   EntityManager  $em
     * @param   CacheService   $cacheService
     */
    public function __construct(EntityManager $em, CacheService $cacheService)
    {
        parent::__construct($em, new ClassMetadata($this->entity));
        $this->cacheService = $cacheService;
    }
}
