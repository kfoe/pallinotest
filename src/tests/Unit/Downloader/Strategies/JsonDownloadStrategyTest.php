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
use App\Downloader\Strategies\JsonDownloadStrategy;

/**
 * @internal
 */
class JsonDownloadStrategyTest extends TestCase
{
    public function testSuccessDownload(): void
    {
        $mock = new MockHandler([
            new GuzzleResponse(Response::HTTP_OK, [], json_encode([
                ['id' => '1', 'name' => 'Foo'],
                ['id' => '2', 'name' => 'Bar'],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->instance(ClientInterface::class, new Client(['handler' => $handlerStack]));

        /** @var JsonDownloadStrategy $strategy */
        $strategy = $this->app->make(JsonDownloadStrategy::class);

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

        /** @var JsonDownloadStrategy $strategy */
        $strategy = $this->app->make(JsonDownloadStrategy::class);

        $strategy->download('foo');
    }
}
