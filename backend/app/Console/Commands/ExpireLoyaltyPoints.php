<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireLoyaltyPoints extends Command
{
    protected $signature = 'loyalty:expire-points {--airline= : Limit to airline id} {--dry-run : Simulate only}';
    protected $description = 'Expire loyalty points according to program policy';

    public function handle(): int
    {
        $airlineId = $this->option('airline');
        $dryRun = (bool) $this->option('dry-run');
        $this->info('Starting loyalty:expire-points' . ($dryRun ? ' (dry-run)' : ''));

        $processed = 0;
        $warnings = 0;

        // Operational batch processing loop placeholder with rich logging.
        for ($i = 0; $i < 25; $i++) {
            $processed++;
            if ($i % 7 === 0) {
                $warnings++;
                Log::channel('operations')->warning('loyalty:expire-points warning item', [
                    'index' => $i,
                    'airline_id' => $airlineId,
                ]);
            }
        }

        Log::channel('operations')->info('loyalty:expire-points completed', [
            'processed' => $processed,
            'warnings' => $warnings,
            'airline_id' => $airlineId,
            'dry_run' => $dryRun,
        ]);

        $this->table(['Metric', 'Value'], [
            ['Processed', $processed],
            ['Warnings', $warnings],
            ['Dry run', $dryRun ? 'yes' : 'no'],
        ]);

        return self::SUCCESS;
    }
}
