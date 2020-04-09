<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Spagi\Watcher\WatchByIdFromXml;
use Spagi\Watcher\WatchesCacheProvider;
use Spagi\Watcher\XmlWatchLoader;

final class WatchByIdFromXmlTest extends TestCase
{
    public function test_invoke(): void
    {
        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
            ->shouldReceive(['getFromCache' => null, 'addToCache' => '']);


        $expected = [
            'id' => 1,
            'title' => 'STRING',
            'price' => 10,
            'desc' => 'STRING',
        ];

        $loader = Mockery::mock(XmlWatchLoader::class)
            ->shouldReceive('loadByIdFromXml')
            ->with(1)
            ->andReturn([
                'id' => 1,
                'title' => 'STRING',
                'price' => 10,
                'desc' => 'STRING',
            ]);

        $watchByIdFromXml = new WatchByIdFromXml($cacheProvider->getMock(), $loader->getMock());
        $result = $watchByIdFromXml->__invoke(\Spagi\Watcher\WatchId::createFromInteger(1));

        static::assertSame($expected, $result);
    }


    public function test_invoke_with_cache(): void
    {

        $expected = [
            'id' => 1,
            'title' => 'STRING',
            'price' => 10,
            'desc' => 'STRING',
        ];

        $id = \Spagi\Watcher\WatchId::createFromInteger(1);

        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
            ->shouldReceive('getFromCache')
            ->with($id)
            ->andReturn($expected);

        $loader = Mockery::mock(XmlWatchLoader::class)
            ->shouldReceive('loadByIdFromXml')
            ->with(1)
            ->andReturn([
                'id' => 1,
                'title' => 'STRING',
                'price' => 10,
                'desc' => 'STRING',
            ]);

        $watchByIdFromXml = new WatchByIdFromXml($cacheProvider->getMock(), $loader->getMock());
        $result = $watchByIdFromXml->__invoke($id);

        static::assertSame($expected, $result);
    }

    public function test_invoke_notfound(): void
    {
        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
        ->shouldReceive('getFromCache')
        ->andReturnNull();

        $expected = [];

        $loader = Mockery::mock(XmlWatchLoader::class)
            ->shouldReceive('loadByIdFromXml')
            ->with(2)
            ->andReturn([]);

        $watchByIdFromXml = new WatchByIdFromXml($cacheProvider->getMock(), $loader->getMock());

        $result = $watchByIdFromXml->__invoke(\Spagi\Watcher\WatchId::createFromInteger(2));

        static::assertSame($expected, $result);

    }
}