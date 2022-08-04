<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Setting\Entity\Contract\SettingInterface;
use Ares\Setting\Entity\Setting;
use Ares\Setting\Repository\SettingRepository;
use Ares\Setting\Service\GetMultipleSettingsService;
use Ares\Setting\Service\UpdateSettingService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class SettingController
 *
 * @package Ares\Setting\Controller
 */
class SettingController extends BaseController
{
    /**
     * SettingsController constructor.
     *
     * @param   ValidationService           $validationService
     * @param   SettingRepository           $settingsRepository
     * @param   UpdateSettingService        $updateSettingsService
     * @param   GetMultipleSettingsService  $getMultipleSettingsService
     */
    public function __construct(
        private ValidationService $validationService,
        private SettingRepository $settingsRepository,
        private UpdateSettingService $updateSettingsService,
        private GetMultipleSettingsService $getMultipleSettingsService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getSettingByKey(Request $request, Response $response, array $args): Response
    {
        /** @var string $settingKey */
        $settingKey = $args['key'];

        /** @var Setting $configData */
        $configData = $this->settingsRepository->get($settingKey, 'key', false, false);

        return $this->respond($response,response()->setData($configData));
    }

        /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function getMultipleSettings(Request $request, Response $response, array $args): Response
    {
        /** @var string $settingKey */
        $settingKeys = $args['keys'];

        $customResponse = $this->getMultipleSettingsService->execute($settingKeys);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getAllSettings(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $settings = $this->settingsRepository->getPaginatedList($this->settingsRepository->getDataObjectManager(), $page, $resultPerPage);

        return $this->respond($response, response()->setData($settings));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function editSetting(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            SettingInterface::COLUMN_KEY => 'required',
            SettingInterface::COLUMN_VALUE => 'required'
        ]);

        $customResponse = $this->updateSettingsService->update($parsedData);

        return $this->respond($response, $customResponse);
    }
}
