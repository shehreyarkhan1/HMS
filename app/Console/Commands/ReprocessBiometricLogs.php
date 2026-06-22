<?php

namespace App\Console\Commands;

use App\Http\Controllers\Biometric\BiometricController;
use Illuminate\Console\Command;

class ReprocessBiometricLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biometric:reprocess';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reprocess unmatched biometric punches after employee mapping';

    /**
     * Execute the console command.
     */
    public function handle(BiometricController $controller): void
    {
        $this->info('Reprocessing unmatched biometric logs...');

        // Controller ka method call ho raha hai
        $controller->reprocessUnmatched();

        $this->info('Done.');
    }
}
