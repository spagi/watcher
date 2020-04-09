<?php  declare(strict_types=1);

namespace Spagi\Watcher;

class WatchByIdFromMysqlQuery
{
    /** @var WatchesCacheProvider */
    private $watchesCacheProvider;

    /** @var MySqlWatchRepository */
    private $mySqlWatchRepository;

    public function __construct(WatchesCacheProvider $watchesCacheProvider, MySqlWatchRepository $mySqlWatchRepository)
    {
        $this->watchesCacheProvider = $watchesCacheProvider;
        $this->mySqlWatchRepository = $mySqlWatchRepository;
    }

    public function __invoke(WatchId $id): array
    {
        $entity = $this->watchesCacheProvider->getFromCache($id);

        if($entity !== null) {
            return $entity;
        }

        $entity = (array)$this->mySqlWatchRepository->getWatchById($id->toInt());

        $this->watchesCacheProvider->addToCache($id, $entity);

        return $entity;
    }
}