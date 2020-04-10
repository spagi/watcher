<?php

namespace Spagi\Watcher;

use Symfony\Component\HttpFoundation\JsonResponse;

class WatchController
{
    private $watchByIdFromMysqlQuery;
    private $watchByIdFromXml;

    public function __construct(WatchByIdFromMysqlQuery $watchByIdFromMysqlQuery, WatchByIdFromXml $watchByIdFromXml)
    {
        $this->watchByIdFromMysqlQuery = $watchByIdFromMysqlQuery;
        $this->watchByIdFromXml = $watchByIdFromXml;
    }

    public function getByIdAction(string $id): JsonResponse
    {
        try {
            $watchId = WatchId::createFromString($id);
        } catch (InvalidArgumentException $e) {
            return JsonResponse::create(null, JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $watchEntity = $this->watchByIdFromMysqlQuery->__invoke($watchId);
        } catch (MySqlWatchNotFoundException $e) {
            return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
        } catch (MySqlRepositoryException $e) {
            return JsonResponse::create(null, JsonResponse::HTTP_GATEWAY_TIMEOUT);
        }


        if (empty($watchEntity)) {
            try {
                $watchEntity = $this->watchByIdFromXml->__invoke($watchId);

                if (empty($watchId)) {
                    return JsonResponse::create(null, JsonResponse::HTTP_NOT_FOUND);
                }

            } catch (XmlLoaderException $e) {
                return JsonResponse::create(null, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return JsonResponse::create(json_encode($watchEntity), JsonResponse::HTTP_OK);
    }
}