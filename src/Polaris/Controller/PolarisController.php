<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Polaris\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Service\ValidationService;
use Ares\Polaris\Service\MonthlyPeakService;
use Ares\Polaris\Service\WeeklyPeakService;
use Ares\Polaris\Service\YearlyPeakService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PolarisController
 *
 * @package Ares\Polaris\Controller
 */
class PolarisController extends BaseController
{
    /**
     * PolarisController constructor.
     *
     * @param ValidationService    $validationService
     */
    public function __construct(
        private ValidationService $validationService,
        private WeeklyPeakService $weeklyPeakService,
        private MonthlyPeakService $monthlyPeakService,
        private YearlyPeakService $yearlyPeakService
    ) {}

    /**
     * Retrieve weekly online peak.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws PolarisException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getWeekly(Request $request, Response $response): Response
    {
        $customResponse = $this->weeklyPeakService->execute();

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Retrieve monthly online peak.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws PolarisException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getMonthly(Request $request, Response $response): Response
    {
        $customResponse = $this->monthlyPeakService->execute();

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Retrieve yearly online peak.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws PolarisException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getYearly(Request $request, Response $response): Response
    {
        $customResponse = $this->yearlyPeakService->execute();

        return $this->respond(
            $response,
            $customResponse
        );
    }
}