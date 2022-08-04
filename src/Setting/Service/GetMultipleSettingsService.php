<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */
namespace Ares\Setting\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Setting\Entity\Setting;
use Ares\Setting\Exception\SettingException;
use Ares\Setting\Repository\SettingRepository;
use Ares\Setting\Interfaces\Response\SettingResponseCodeInterface;

/**
 * Class GetMultipleSettingsService
 *
 * @package Ares\Setting\Service
 */
class GetMultipleSettingsService
{
    /**
     * GetMultipleSettingsService constructor.
     *
     * @param SettingRepository $settingsRepository
     */
    public function __construct(
        private SettingRepository $settingRepository
    ) {}


    /**
     * @param String $keys
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws SettingException
     * @throws NoSuchEntityException
    */
    public function execute(String $keys): CustomResponseInterface
    {
        /** @var string[] $key */
        $keys = explode(',', $keys);

        /** @var Setting[] $configData */
        $configData = [];

        foreach($keys as $key) {
            /** @var Setting $setting */
            $setting = $this->settingRepository->getSettingByKey($key);

            if(!$setting) {
                throw new SettingException(
                    __($key . ' key does not exists.'),
                    SettingResponseCodeInterface::RESPONSE_SETTING_NOT_FOUND,
                    HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
                );
            }

            $configData[$setting->getKey()] = $setting->getValue();
        }

        return response()->setData($configData);
    }
}