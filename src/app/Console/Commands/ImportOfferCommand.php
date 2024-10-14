<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importers\OfferImporter;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ImportOfferCommand extends Command
{
    protected $signature = 'import:offers';

    protected $description = 'Import offers';

    public function handle(OfferImporter $offerImporter): int
    {
        if ($offerImporter->import()) {
            $this->info(__('Successfully imported offers.'));

            return CommandAlias::SUCCESS;
        }

        $this->error(__('Failed to import offers.'));

        return CommandAlias::FAILURE;
    }
}
