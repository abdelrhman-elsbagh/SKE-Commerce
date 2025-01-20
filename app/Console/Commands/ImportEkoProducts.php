<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ItemController;

class ImportEkoProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eko:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and import products from EkoStore API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info('ImportEkoProducts Command Started');
        $controller = new ItemController();
        $controller->fetchAndImportEkoStoreProducts();
        $this->info('EkoStore products imported successfully.');
        \Log::info('ImportEkoProducts Command Finished');
        return Command::SUCCESS;
    }
}
