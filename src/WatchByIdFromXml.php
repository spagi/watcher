<?php declare(strict_types=1);

namespace Spagi\Watcher;

class WatchByIdFromXml
{
    /** @var @var WatchesCacheProvider */
    private $watchesCacheProvider;

    /** @var XmlWatchLoader */
    private $xmlWatchLoader;

    public function __construct(WatchesCacheProvider $watchesCacheProvider, XmlWatchLoader $xmlWatchLoader)
    {
        $this->watchesCacheProvider = $watchesCacheProvider;
        $this->xmlWatchLoader = $xmlWatchLoader;
    }

    public function __invoke(WatchId $id): array
    {
        $entity = $this->watchesCacheProvider->getFromCache($id);

        if($entity !== null) {
            return $entity;
        }

        $entity = (array)$this->xmlWatchLoader->loadByIdFromXml($id->toString());

        if (!empty($entity)) {
            $this->watchesCacheProvider->addToCache($id, $entity);
        }

        return $entity;
    }
}