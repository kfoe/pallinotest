<?php

namespace Tests\Unit\Downloader\Strategies;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use App\Downloader\Strategies\CsvDownloadStrategy;

/**
 * @internal
 */
class CsvDownloadStrategyTest extends TestCase
{
    public function testSuccessDownload(): void
    {
        $mock = new MockHandler([
            new GuzzleResponse(Response::HTTP_OK, [], "id,name\n1,Foo\n2,Bar"),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->instance(ClientInterface::class, new Client(['handler' => $handlerStack]));

        /** @var CsvDownloadStrategy $strategy */
        $strategy = $this->app->make(CsvDownloadStrategy::class);

        $dataset = $strategy->download('foo');

        $this->assertSame([
            [
                'id' => '1',
                'name' => 'Foo',
            ],
            [
                'id' => '2',
                'name' => 'Bar',
            ],
        ], $dataset);
    }

    public function testCatchRequestExceptionAndThrowRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Request Error: Error Communicating with Server');

        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'foo')),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->instance(ClientInterface::class, new Client(['handler' => $handlerStack]));

        /** @var CsvDownloadStrategy $strategy */
        $strategy = $this->app->make(CsvDownloadStrategy::class);

        $strategy->download('foo');
    }
}
