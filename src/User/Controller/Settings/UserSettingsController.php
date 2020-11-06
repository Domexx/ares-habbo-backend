<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller\Settings;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
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
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var ChangeGeneralSettingsService
     */
    private ChangeGeneralSettingsService $changeGeneralSettingsService;

    /**
     * @var ChangePasswordService
     */
    private ChangePasswordService $changePasswordService;

    /**
     * @var ChangeEmailService
     */
    private ChangeEmailService $changeEmailService;

    /**
     * @var ChangeUsernameService
     */
    private ChangeUsernameService $changeUsernameService;

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
        ValidationService $validationService,
        ChangeGeneralSettingsService $changeGeneralSettingsService,
        ChangePasswordService $changePasswordService,
        ChangeEmailService $changeEmailService,
        ChangeUsernameService $changeUsernameService
    ) {
        $this->validationService = $validationService;
        $this->changeGeneralSettingsService = $changeGeneralSettingsService;
        $this->changePasswordService = $changePasswordService;
        $this->changeEmailService = $changeEmailService;
        $this->changeUsernameService = $changeUsernameService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserSettingsException
     * @throws ValidationException
     * @throws DataObjectManagerException
     */
    public function changeGeneralSettings(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'block_following' => 'required',
            'block_friendrequests' => 'required',
            'block_roominvites' => 'required',
            'block_camera_follow' => 'required',
            'block_alerts' => 'required',
            'ignore_bots' => 'required',
            'ignore_pets' => 'required'
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
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changePassword(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'new_password' => 'required',
            'password' => 'required'
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
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changeEmail(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'email' => 'required',
            'password' => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->changeEmailService->execute(
            $user,
            $parsedData['email'],
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
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws ValidationException
     */
    public function changeUsername(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required',
            'password' => 'required'
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
