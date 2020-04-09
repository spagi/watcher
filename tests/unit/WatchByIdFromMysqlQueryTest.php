<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Spagi\Watcher\MySqlWatchRepository;
use Spagi\Watcher\WatchByIdFromMysqlQuery;
use Spagi\Watcher\WatchesCacheProvider;
use Spagi\Watcher\MySqlWatchDTO;

class WatchByIdFromMysqlQueryTest extends TestCase
{
    /**
     * @covers \Spagi\Watcher\WatchByIdFromMysqlQuery::__invoke
     */
    public function test_invoke(): void
    {

        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
            ->shouldReceive(['getFromCache' => null, 'addToCache' => '']);

        $dto = new MySqlWatchDTO(1, 'some title', 10, 'some desc');
        $repository = Mockery::mock(MySqlWatchRepository::class)
            ->shouldReceive('getWatchById')
            ->with(1)
            ->andReturn($dto);

        $watchByIdFromMysqlQuery = new WatchByIdFromMysqlQuery($cacheProvider->getMock(), $repository->getMock());
        $result = $watchByIdFromMysqlQuery->__invoke(\Spagi\Watcher\WatchId::createFromInteger(1));

        static::assertSame((array)$dto, $result);
    }

    public function test_invoke_with_cache(): void
    {
        $dto = new MySqlWatchDTO(1, 'some title', 10, 'some desc');
        $id = \Spagi\Watcher\WatchId::createFromInteger(1);

        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
            ->shouldReceive('getFromCache')
            ->with($id)
            ->andReturn((array)$dto);

        $repository = Mockery::mock(MySqlWatchRepository::class)
            ->shouldReceive('getWatchById')
            ->with(1)
            ->andReturn($dto);

        $watchByIdFromMysqlQuery = new WatchByIdFromMysqlQuery($cacheProvider->getMock(), $repository->getMock());
        $result = $watchByIdFromMysqlQuery->__invoke($id);

        static::assertSame((array)$dto, $result);
    }

    public function test_invoke_notfound(): void
    {
        $cacheProvider = Mockery::mock(WatchesCacheProvider::class)
            ->shouldReceive('getFromCache')
            ->andReturnNull();

        $repository = Mockery::mock(MySqlWatchRepository::class)
            ->shouldReceive('getWatchById')
            ->with(2)
            ->andThrow( \Spagi\Watcher\MySqlWatchNotFoundException::class);

        $watchByIdFromMysqlQuery = new WatchByIdFromMysqlQuery($cacheProvider->getMock(), $repository->getMock());

        static::expectException('Spagi\Watcher\MySqlWatchNotFoundException');

        $watchByIdFromMysqlQuery->__invoke(\Spagi\Watcher\WatchId::createFromInteger(2));


    }
}