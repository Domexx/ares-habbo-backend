<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Settings\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
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
     * SettingsController constructor.
     *
     * @param   ValidationService       $validationService
     * @param   SettingsRepository      $settingsRepository
     * @param   UpdateSettingsService   $updateSettingsService
     */
    public function __construct(
        private ValidationService $validationService,
        private SettingsRepository $settingsRepository,
        private UpdateSettingsService $updateSettingsService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
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
                $page,
                $resultPerPage
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
     * @throws NoSuchEntityException
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
