<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Settings\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Settings\Entity\Setting;
use Ares\Settings\Repository\SettingsRepository;

/**
 * Class UpdateSettingsService
 *
 * @package Ares\Settings\Service
 */
class UpdateSettingsService
{
    /**
     * UpdateSettingsService constructor.
     *
     * @param   SettingsRepository  $settingsRepository
     */
    public function __construct(
        private SettingsRepository $settingsRepository
    ) {}

    /**
     * @param $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function update($data): CustomResponseInterface
    {
        /** @var string $key */
        $key = $data['key'];

        /** @var string $value */
        $value = $data['value'];

        /** @var Setting $configData */
        $configData = $this->settingsRepository->get($key, 'key');
        $configData->setValue($value);
        $configData = $this->settingsRepository->save($configData);

        return response()
            ->setData(
                $configData
            );
    }
}
