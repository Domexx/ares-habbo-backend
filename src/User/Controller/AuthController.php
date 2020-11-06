<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Ban\Exception\BanException;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
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
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var LoginService
     */
    private LoginService $loginService;

    /**
     * @var RegisterService
     */
    private RegisterService $registerService;

    /**
     * @var TicketService
     */
    private TicketService $ticketService;

    /**
     * @var DetermineIpService
     */
    private DetermineIpService $determineIpService;

    /**
     * @var Config
     */
    private Config $config;

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
        ValidationService $validationService,
        LoginService $loginService,
        RegisterService $registerService,
        TicketService $ticketService,
        DetermineIpService $determineIpService,
        Config $config
    ) {
        $this->validationService = $validationService;
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->ticketService = $ticketService;
        $this->determineIpService = $determineIpService;
        $this->config = $config;
    }

    /**
     * Logs the User in and parses a generated Token into response
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws BanException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws LoginException
     * @throws ValidateException
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
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws AuthenticationException
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
