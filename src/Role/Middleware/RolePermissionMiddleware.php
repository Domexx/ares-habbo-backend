<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Middleware;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Service\CheckAccessService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Class RolePermissionMiddleware
 *
 * @package Ares\Role\Middleware
 */
class RolePermissionMiddleware implements MiddlewareInterface
{
    /**
     * RolePermissionMiddleware constructor.
     *
     * @param CheckAccessService $checkAccessService
     */
    public function __construct(
        private CheckAccessService $checkAccessService
    ) {}

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws RoleException|NoSuchEntityException
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $permissionName = $route->getName();

        if ($permissionName === null) {
            return $handler->handle($request);
        }

        $userId = $this->fetchUserId($request);

        $isPermitted = $this->checkAccessService->execute($userId, $permissionName);

        if (!$isPermitted) {
            throw new RoleException(
                __('You dont have the special rights to execute that action'),
                RoleResponseCodeInterface::RESPONSE_ROLE_NO_RIGHTS_TO_EXECUTE_ACTION,
                HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
            );
        }

        return $handler->handle($request);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return int|null
     */
    private function fetchUserId(ServerRequestInterface $request): ?int
    {
        /** @var array $user */
        $user = $request->getAttribute('ares_uid');
        if (isset($user)) {
            return json_decode(json_encode($user), true);
        }

        return null;
    }
}
