<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Controller\Status;

use Ares\Framework\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class StatusController
 *
 * @package Ares\Framework\Controller\Status
 */
class StatusController extends BaseController
{
    /** @var string */
    private const API_NAME = 'Ares API';

    /** @var string */
    private const API_VERSION = '1.0';

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getStatus(Request $request, Response $response): Response
    {
        /** @var Response $customResponse */
        $customResponse = response()->setData([
            'api' => self::API_NAME,
            'version' => self::API_VERSION
        ]);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
