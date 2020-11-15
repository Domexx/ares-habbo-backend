<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller;

use Ares\Ban\Exception\BanException;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Ares\User\Exception\LoginException;
use Ares\User\Exception\RegisterException;
use Ares\User\Service\Auth\DetermineIpService;
use Ares\User\Service\Auth\LoginService;
use Ares\User\Service\Auth\RegisterService;
use Ares\User\Service\Auth\TicketService;
use Exception;
use PHLAK\Config\Config;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class AuthController
 *
 * @package Ares\User\Controller\Auth
 */
class AuthController extends BaseController
{
    /**
     * AuthController constructor.
     *
     * @param ValidationService $validationService
     * @param LoginService $loginService
     * @param RegisterService $registerService
     * @param TicketService $ticketService
     * @param DetermineIpService $determineIpService
     * @param Config $config
     */
    public function __construct(
        private ValidationService $validationService,
        private LoginService $loginService,
        private RegisterService $registerService,
        private TicketService $ticketService,
        private DetermineIpService $determineIpService,
        private Config $config
    ) {}

    /**
     * Logs the User in and parses a generated Token into response
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws BanException
     * @throws DataObjectManagerException
     * @throws LoginException
     * @throws ValidateException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function login(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required',
            'password' => 'required'
        ]);

        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData['ip_current'] = $determinedIp;

        $customResponse = $this->loginService->login($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Registers the User and parses a generated Token into the response
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws Exception
     */
    public function register(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required|min:3|max:12',
            'mail' => 'required|email|min:9',
            'look' => 'required',
            'gender' => 'required|default:M|regex:/[M.F]/',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        /** @var string $determinedIp */
        $determinedIp = $this->determineIpService->execute();

        $parsedData['ip_register'] = $determinedIp;
        $parsedData['ip_current'] = $determinedIp;

        $customResponse = $this->registerService->register($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Gets the viable Looks for the registration
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws RegisterException
     */
    public function viableLooks(Request $request, Response $response): Response
    {
        /** @var array $boyLooks */
        $boyLooks = $this->config->get('hotel_settings.register.looks.boy');

        /** @var array $girlLooks */
        $girlLooks = $this->config->get('hotel_settings.register.looks.girl');

        if (!is_array($boyLooks) || !is_array($girlLooks)) {
            throw new RegisterException(__('There are no viable Looks available'));
        }

        /** @var array $boyList */
        $boyList = array_values($boyLooks);

        /** @var array $girlList */
        $girlList = array_values($girlLooks);

        return $this->respond(
            $response,
            response()
                ->setData([
                    'looks' => [
                        'boys' => $boyList,
                        'girls' => $girlList
                    ]
                ])
        );
    }

    /**
     * Gets a new Ticket for the current User
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function ticket(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = user($request);

        /** @var TicketService $ticket */
        $ticket = $this->ticketService->generate($user);

        return $this->respond(
            $response,
            response()
                ->setData([
                    'ticket' => $ticket
                ])
        );
    }

    /**
     * Returns a response without the Authorization header
     * We could blacklist the token with redis-cache
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request, Response $response): Response
    {
        return $response->withoutHeader('Authorization');
    }
}
