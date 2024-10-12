<?php

namespace Tests\Unit\Downloader\Dtos;

use Tests\TestCase;
use App\Downloader\Dtos\DownloadedShop;

/**
 * @internal
 */
class DownloadedShopTest extends TestCase
{
    public function testNewInstance(): void
    {
        $downloadedShop = new DownloadedShop(
            789,
            'Test Shop',
            '123 Test Street',
            'UK'
        );

        $this->assertSame(789, $downloadedShop->externalId);
        $this->assertSame('Test Shop', $downloadedShop->name);
        $this->assertSame('123 Test Street', $downloadedShop->address);
        $this->assertSame('UK', $downloadedShop->country);
    }

    public function testToArray(): void
    {
        $downloadedShop = new DownloadedShop(
            789,
            'Test Shop',
            '123 Test Street',
            'UK'
        );

        $expectedArray = [
            'external_id' => 789,
            'name' => 'Test Shop',
            'address' => '123 Test Street',
            'country' => 'UK',
        ];

        $this->assertSame($expectedArray, $downloadedShop->toArray());
    }
}
