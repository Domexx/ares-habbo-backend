<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Polaris\Repository;

use Ares\Polaris\Entity\Peak;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Model\Query\Collection;
use Illuminate\Database\Query\Expression;

/**
 * Class PeakRepository
 *
 * @package Ares\Polaris\Repository
 */
class PeakRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_POLARIS_PEAKS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_POLARIS_PEAKS_COLLECTION_';

    /** @var string */
    protected string $entity = Peak::class;

    /**
     *
     * @return Collection
     * @throws DataObjectManagerException
    */
    public function getWeekPeak(): Collection
    {
        $weekAgoTimeStamp = strtotime("-1 week");
        $today = date("Y-m-d");
        $weekAgo = date("Y-m-d", $weekAgoTimeStamp);

        $searchCriteria = $this->getDataObjectManager()
        ->select("*")
        ->whereBetween("date", [$weekAgo, $today]);

        return $this->getList($searchCriteria);
    }

    /**
     *
     * @return Collection
     * @throws DataObjectManagerException
    */
    public function getMonthPeak(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
        ->selectRaw("id, MONTHNAME(date) as month, SUM(count) as count")
        ->whereRaw("YEAR(date) = YEAR(CURRENT_DATE)")
        ->groupByRaw("MONTH(date)");

        return $this->getList($searchCriteria);
    }

    /**
     *
     * @return Collection
     * @throws DataObjectManagerException
    */
    public function getYearPeak(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
        ->selectRaw("id, YEAR(date) as year, SUM(count) as count")
        ->groupByRaw("YEAR(date)");

        return $this->getList($searchCriteria);
    }
}