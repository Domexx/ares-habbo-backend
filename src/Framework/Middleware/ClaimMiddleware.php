<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Middleware;

use Ares\Framework\Service\TokenService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReallySimpleJWT\Token;

/**
 * ClaimMiddleware.
 *
 * @package Ares\Framework\Middleware
 */
class ClaimMiddleware implements MiddlewareInterface
{
    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * The constructor.
     *
     * @param   TokenService  $tokenService
     */
    public function __construct(
        TokenService $tokenService
    ) {
        $this->tokenService = $tokenService;
    }

    /**
     * Invoke middleware.
     *
     * @param   ServerRequestInterface   $request  The request
     * @param   RequestHandlerInterface  $handler  The handler
     *
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authorization = explode(' ', (string)$request->getHeaderLine('Authorization'));
        $type          = $authorization[0] ?? '';
        $credentials   = $authorization[1] ?? '';
        $secret        = $_ENV['TOKEN_SECRET'];

        if ($type === 'Bearer' && Token::validate($credentials, $secret)) {
            // Append valid token
            $parsedToken = Token::parser($credentials, $secret);
            $request     = $request->withAttribute('token', $parsedToken);

            // Append the user id as request attribute
            $request = $request->withAttribute('ares_uid', Token::getPayload($credentials, $secret)['uid']);
        }

        return $handler->handle($request);
    }
}
