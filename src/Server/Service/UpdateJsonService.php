<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Server\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Setting\Repository\SettingRepository;

/**
 * Class UpdateJsonService
 *
 * @package Ares\Server\Service
 */
class UpdateJsonService {
    private string $jsonFile;

    /**
     * UpdateJsonService constructor.
     *
     */
    public function __construct(
        private SettingRepository $settingsRepository
    ) {}

    /**
     * @param string $key
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute(string $key, array $data): CustomResponseInterface
    {
        /** @var Setting $setting */
        $setting = $this->settingsRepository->getSettingByKey($key);

        $this->jsonFile = $setting->getValue();

        file_put_contents($this->jsonFile, $data['data']);

        return response()->setData(true);
    }

    /**
     * @param string $key
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function executeKeys(string $key, array $data): CustomResponseInterface
    {
        /** @var Setting $setting */
        $setting = $this->settingsRepository->getSettingByKey($key);

        $this->jsonFile = $setting->getValue();

        $context = file_get_contents($this->jsonFile);

        $json = json_decode($context, true);

        foreach($data as $key => $value) {
            $json[$key] = $value;
        }

        $newJson = json_encode($json);

        file_put_contents($this->jsonFile, $newJson);

        return response()->setData(true);
    }
}