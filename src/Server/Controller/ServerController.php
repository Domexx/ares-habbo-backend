<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Server\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Service\ValidationService;
use Ares\Server\Service\BadgeService;
use Ares\Server\Service\GetJsonService;
use Ares\Server\Service\UpdateJsonService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;

/**
 * Class ServerController
 *
 * @package Ares\Server\Controller
 */
class ServerController extends BaseController
{
    /**
     * ServerController constructor.
     *
     */
    public function __construct(
        private GetJsonService $getJsonService,
        private UpdateJsonService $updateJsonService,
        private ValidationService $validationService,
        private BadgeService $badgeService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
    */
    public function getNitroTexts(Request $request, Response $response): Response
    {
        $customResponse = $this->getJsonService->execute('external_texts_json_location');

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
    */
    public function editNitroTexts(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'data' => 'required'
        ]);

        $customResponse = $this->updateJsonService->execute('external_texts_json_location', $parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
    */
    public function verifyBadgeCode(Request $request, Response $response, array $args): Response
    {
        $badgeCode = $args['code'];

        $exists = $this->badgeService->badgeCodeExists($badgeCode);

        return $this->respond($response, response()->setData(['valid' => !$exists]));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws NoSuchEntityException
    */
    public function uploadBadge(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'code' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);

        /** @var array $uploadedFiles */
        $uploadedFiles = $request->getUploadedFiles();

        /** @var UploadedFile $image */
        $file = $uploadedFiles['image'];

        $customResponse = $this->badgeService->execute($file, $parsedData);
    
        return $this->respond(
            $response,
            $customResponse
        );
    }
}
