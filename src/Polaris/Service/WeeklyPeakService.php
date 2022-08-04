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
 * Class WeeklyPeakService
 *
 * @package Ares\Polaris\Service
 */
class WeeklyPeakService
{
    /**
     * WeeklyPeakService constructor.
     *
     * @param PeakRepository $peakRepository
     */
    public function __construct(
        private PeakRepository $peakRepository
    ) {}

    /**
     * Retrieve weekly onlines peak history.
     *
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(): CustomResponseInterface
    {
        $week = $this->peakRepository->getWeekPeak();

        return response()->setData($week);
    }
}