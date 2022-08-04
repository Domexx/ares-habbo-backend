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
 * Class YearlyPeakService
 *
 * @package Ares\Polaris\Service
 */
class YearlyPeakService
{
    /**
     * YearlyPeakService constructor.
     *
     * @param PeakRepository $peakRepository
     */
    public function __construct(
        private PeakRepository $peakRepository
    ) {}

    /**
     * Retrieve yearly onlines peak history.
     *
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(): CustomResponseInterface
    {
        $year = $this->peakRepository->getYearPeak();

        return response()->setData($year);
    }
}