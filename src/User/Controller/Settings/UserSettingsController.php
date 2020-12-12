<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller\Settings;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\Contract\UserSettingInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Service\Settings\ChangeEmailService;
use Ares\User\Service\Settings\ChangeGeneralSettingsService;
use Ares\User\Service\Settings\ChangePasswordService;
use Ares\User\Service\Settings\ChangeUsernameService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserSettingsController
 *
 * @package Ares\User\Controller\Settings
 */
class UserSettingsController extends BaseController
{
    /**
     * UserSettingsController constructor.
     *
     * @param ValidationService            $validationService
     * @param ChangeGeneralSettingsService $changeGeneralSettingsService
     * @param ChangePasswordService        $changePasswordService
     * @param ChangeEmailService           $changeEmailService
     * @param ChangeUsernameService        $changeUsernameService
     */
    public function __construct(
        private ValidationService $validationService,
        private ChangeGeneralSettingsService $changeGeneralSettingsService,
        private ChangePasswordService $changePasswordService,
        private ChangeEmailService $changeEmailService,
        private ChangeUsernameService $changeUsernameService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function changeGeneralSettings(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserSettingInterface::COLUMN_BLOCK_FOLLOWING => 'required',
            UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS => 'required',
            UserSettingInterface::COLUMN_BLOCK_ROOMINVITES => 'required',
            UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW => 'required',
            UserSettingInterface::COLUMN_BLOCK_ALERTS => 'required',
            UserSettingInterface::COLUMN_IGNORE_BOTS => 'required',
            UserSettingInterface::COLUMN_IGNORE_PETS => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changeGeneralSettingsService
            ->execute(
                $user,
                $parsedData
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changePassword(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_PASSWORD => 'required',
            'new_password' => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changePasswordService
            ->execute(
                $user,
                $parsedData['new_password'],
                $parsedData['password']
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changeEmail(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_MAIL => 'required',
            UserInterface::COLUMN_PASSWORD => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changeEmailService->execute(
            $user,
            $parsedData['mail'],
            $parsedData['password']
        );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changeUsername(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required',
            UserInterface::COLUMN_PASSWORD => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changeUsernameService->execute(
            $user,
            $parsedData['username'],
            $parsedData['password']
        );

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
