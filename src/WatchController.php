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

        $watchEntity = $this->watchByIdFromMysqlQuery->__invoke($watchId);

        if (empty($watchEntity)) {
            $watchEntity = $this->watchByIdFromXml->__invoke($watchId);
        }

        return JsonResponse::create(json_encode($watchEntity), JsonResponse::HTTP_OK);
    }
}