<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Settings\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Settings\Entity\Setting;
use Ares\Settings\Exception\SettingsException;
use Ares\Settings\Repository\SettingsRepository;
use Ares\Settings\Service\UpdateSettingsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class SettingsController
 *
 * @package Ares\Settings\Controller
 */
class SettingsController extends BaseController
{
    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var SettingsRepository
     */
    private SettingsRepository $settingsRepository;

    /**
     * @var UpdateSettingsService
     */
    private UpdateSettingsService $updateSettingsService;

    /**
     * SettingsController constructor.
     *
     * @param   ValidationService       $validationService
     * @param   SettingsRepository      $settingsRepository
     * @param   UpdateSettingsService   $updateSettingsService
     */
    public function __construct(
        ValidationService $validationService,
        SettingsRepository $settingsRepository,
        UpdateSettingsService $updateSettingsService
    ) {
        $this->validationService     = $validationService;
        $this->settingsRepository    = $settingsRepository;
        $this->updateSettingsService = $updateSettingsService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws SettingsException
     * @throws ValidationException
     */
    public function get(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'key' => 'required'
        ]);

        /** @var string $key */
        $key = $parsedData['key'];

        /** @var Setting $configData */
        $configData = $this->settingsRepository->get($key, 'key');

        if (!$configData) {
            throw new SettingsException(__('Key not found in Config'));
        }

        return $this->respond(
            $response,
            response()
                ->setData($configData)
        );
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
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $settings = $this->settingsRepository
            ->getPaginatedList(
                $this->settingsRepository->getDataObjectManager(),
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($settings)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws SettingsException
     * @throws ValidationException
     */
    public function set(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'key'   => 'required',
            'value' => 'required'
        ]);

        $customResponse = $this->updateSettingsService->update($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
