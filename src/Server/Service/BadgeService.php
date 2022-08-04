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
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Server\Exception\BadgeExistsException;
use Ares\Server\Interfaces\Response\BadgeResponseCodeInterface;
use Ares\Setting\Repository\SettingRepository;
use Slim\Psr7\UploadedFile;

/**
 * Class BadgeService
 *
 * @package Ares\Server\Service
 */
class BadgeService {
    private string $badgeFolder;

    /**
     * BadgeService constructor.
     *
     */
    public function __construct(
        private SettingRepository $settingsRepository,
        private UpdateJsonService $updateJsonService
    ) {
        /** @var Setting $setting */
        $setting = $this->settingsRepository->getSettingByKey('badges_folder_location');

        $this->badgeFolder = $setting->getValue();
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws InvalidArgumentException
     * @throws NoSuchEntityException
     */
    public function execute(UploadedFile $file, array $data): CustomResponseInterface
    {
        $codeExists = $this->badgeCodeExists($data['code']);

        if($codeExists) {
            throw new BadgeExistsException(
                __('Badge Code is already registered.'),
                BadgeResponseCodeInterface::BADGE_ALREADY_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $badgeName = "badge_name_" . $data['code'];
        $badgeDesc = "badge_desc_" . $data['code'];

        $badgeData = [
            $badgeName => $data['name'],
            $badgeDesc => $data['description']
        ];

        $filepath = $this->badgeFolder . $file->getClientFilename() . '.gif';

        if(!is_writable($this->badgeFolder)) {
            throw new BadgeExistsException(
                __('Badge Folder is not writable or does not exists.'),
                BadgeResponseCodeInterface::BADGE_FOLDER_ERROR,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $file->moveTo($filepath);
        $this->updateJsonService->executeKeys('external_texts_json_location', $badgeData);

        return response()->setData([]);
    }

    /**
     * @param string $code
     * @param string $directory
     *
     * @return bool
     */
    public function badgeCodeExists(string $code): bool
    {
        if(!is_writable($this->badgeFolder)) {
            throw new BadgeExistsException(
                __('Badge Folder is not writable or does not exists.'),
                BadgeResponseCodeInterface::BADGE_FOLDER_ERROR,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return file_exists($this->badgeFolder . $code . '.gif');
    }
}