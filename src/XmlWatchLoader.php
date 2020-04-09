<?php  declare(strict_types=1);

namespace Spagi\Watcher;

interface XmlWatchLoader
{
    /**
     * @param string $watchIdentification
     *
     * @return array|null
     *
     * @throws XmlLoaderException May be thrown on a fatal error, such as
     * XML file containing data of watches
     * could not be loaded or parsed.
     */
    public function loadByIdFromXml(string $watchIdentification): array;
}