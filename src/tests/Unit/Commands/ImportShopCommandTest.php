<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Importer\ShopImporter;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 */
class ImportShopCommandTest extends TestCase
{
    public function testHandleFailure(): void
    {
        $this->mock(ShopImporter::class, function (MockInterface $mock) {
            $mock->expects('import')->andReturn(false)();
        });

        $this->artisan('import:shops')
            ->expectsOutput(__('Failed to import shops.'))
            ->assertExitCode(Command::FAILURE);
    }

    public function testHandleSuccess(): void
    {
        $this->mock(ShopImporter::class, function (MockInterface $mock) {
            $mock->expects('import')->andReturn(true)();
        });

        $this->artisan('import:shops')
            ->expectsOutput(__('Successfully imported shops.'))
            ->assertExitCode(Command::SUCCESS);
    }
}
