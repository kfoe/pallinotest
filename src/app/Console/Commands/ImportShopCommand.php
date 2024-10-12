<?php

namespace App\Console\Commands;

use App\Importer\ShopImporter;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ImportShopCommand extends Command
{
    protected $signature = 'import:shops';

    protected $description = 'Import shops';

    public function handle(ShopImporter $shopImporter): int
    {
        if ($shopImporter->import()) {
            $this->info(__('Successfully imported shops.'));

            return CommandAlias::SUCCESS;
        }

        $this->error(__('Failed to import shops.'));

        return CommandAlias::FAILURE;
    }
}
