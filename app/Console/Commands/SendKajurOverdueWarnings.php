<?php

namespace App\Console\Commands;

use App\Models\Jurusan;
use App\Models\User;
use App\Services\KajurWarningService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendKajurOverdueWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kajur:send-overdue-warnings {--force : Force send even if auto warning is disabled in config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily overdue loan warnings for all departments under Kajur role (disabled by default)';

    protected KajurWarningService $warningService;

    public function __construct(KajurWarningService $warningService)
    {
        parent::__construct();
        $this->warningService = $warningService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isEnabled = config('services.kajur_warning.auto_send', false) || env('KAJUR_AUTO_WARNING', false);

        if (!$isEnabled && !$this->option('force')) {
            $this->info('Kajur auto overdue warning is disabled (KAJUR_AUTO_WARNING=false). Use --force to override.');
            return Command::SUCCESS;
        }

        $this->info('Starting Kajur overdue loan warning process...');

        $jurusans = Jurusan::all();
        $totalSent = 0;
        $totalSkipped = 0;

        foreach ($jurusans as $jurusan) {
            // Find active Kajur for this jurusan
            $kajur = User::role('kepala_jurusan')
                ->where('jurusan_id', $jurusan->id)
                ->first();

            if (!$kajur) {
                // Fallback: any kajur or default system sender
                $kajur = User::role('kepala_jurusan')->first();
            }

            if (!$kajur) {
                $this->warn("No Kepala Jurusan found for jurusan {$jurusan->nama} (ID: {$jurusan->id}). Skipping.");
                continue;
            }

            $stats = $this->warningService->sendBulkWarnings($jurusan->id, $kajur, 50);

            $totalSent += $stats['sent'];
            $totalSkipped += $stats['skipped_antispam'];

            $this->line("Jurusan {$jurusan->nama}: {$stats['sent']} sent, {$stats['skipped_antispam']} skipped (anti-spam), {$stats['failed']} failed.");
        }

        $this->info("Kajur warning process completed: {$totalSent} sent, {$totalSkipped} skipped.");
        Log::info("Kajur automatic overdue warnings completed", [
            'sent' => $totalSent,
            'skipped_antispam' => $totalSkipped,
        ]);

        return Command::SUCCESS;
    }
}
