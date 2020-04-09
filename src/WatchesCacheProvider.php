<?php  declare(strict_types=1);

namespace Spagi\Watcher;

interface WatchesCacheProvider
{
    public function addToCache(WatchId $id, array $entity): void;

    public function getFromCache(WatchId $id): ?array;
}