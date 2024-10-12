<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Importer\OfferImporter;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 */
class ImportOfferCommandTest extends TestCase
{
    public function testHandleFailure(): void
    {
        $this->mock(OfferImporter::class, function (MockInterface $mock) {
            $mock->expects('import')->andReturn(false)();
        });

        $this->artisan('import:offers')
            ->expectsOutput(__('Failed to import offers.'))
            ->assertExitCode(Command::FAILURE);
    }

    public function testHandleSuccess(): void
    {
        $this->mock(OfferImporter::class, function (MockInterface $mock) {
            $mock->expects('import')->andReturn(true)();
        });

        $this->artisan('import:offers')
            ->expectsOutput(__('Successfully imported offers.'))
            ->assertExitCode(Command::SUCCESS);
    }
}
