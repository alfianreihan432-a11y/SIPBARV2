<?php

namespace App\Console\Commands;

use App\Models\BorrowingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark borrowed loans as overdue when return date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting overdue loan marking process...');

        // Use Asia/Jakarta timezone for comparison
        $nowJakarta = now()->timezone('Asia/Jakarta');

        // Find all borrowed items that are overdue
        $overdueLoans = BorrowingRequest::where('status', BorrowingRequest::STATUS_BORROWED)
            ->where(function($query) use ($nowJakarta) {
                $query->whereDate('return_date', '<', $nowJakarta)
                    ->orWhere(function($q) use ($nowJakarta) {
                        $q->whereDate('return_date', '=', $nowJakarta)
                          ->whereNotNull('return_time')
                          ->whereTime('return_time', '<', $nowJakarta);
                    });
            })
            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('No overdue loans to mark.');
            return Command::SUCCESS;
        }

        $this->info("Found {$overdueLoans->count()} overdue loan(s) to mark.");

        $successCount = 0;

        $this->withProgressBar($overdueLoans, function ($loan) use (&$successCount) {
            try {
                // Update status to overdue
                $loan->update([
                    'status' => BorrowingRequest::STATUS_OVERDUE,
                ]);

                $successCount++;

                Log::info('Loan marked as overdue', [
                    'borrowing_request_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'return_date' => $loan->return_date,
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to mark loan as overdue', [
                    'borrowing_request_id' => $loan->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });

        $this->newLine(2);
        $this->info("Overdue marking process completed!");
        $this->info("✓ Successfully marked: {$successCount}");

        return Command::SUCCESS;
    }
}
