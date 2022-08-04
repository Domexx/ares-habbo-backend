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
 * Class GetJsonService
 *
 * @package Ares\Server\Service
 */
class GetJsonService {
    private string $jsonFile;

    /**
     * GetJsonService constructor.
     *
     */
    public function __construct(
        private SettingRepository $settingsRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute($key): CustomResponseInterface
    {
        /** @var Setting $setting */
        $setting = $this->settingsRepository->getSettingByKey($key);

        $this->jsonFile = $setting->getValue();

        $context = file_get_contents($this->jsonFile);

        return response()->setData($context);
    }
}