<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Polaris\Service;

use Ares\Polaris\Repository\PeakRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class MonthlyPeakService
 *
 * @package Ares\Polaris\Service
 */
class MonthlyPeakService
{
    /**
     * MonthlyPeakService constructor.
     *
     * @param PeakRepository $peakRepository
     */
    public function __construct(
        private PeakRepository $peakRepository
    ) {}

    /**
     * Retrieve monthly onlines peak history.
     *
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(): CustomResponseInterface
    {
        $month = $this->peakRepository->getMonthPeak();

        return response()->setData($month);
    }
}