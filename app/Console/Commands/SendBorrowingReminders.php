<?php

namespace App\Console\Commands;

use App\Models\BorrowingRequest;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBorrowingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'borrowing:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send H-1 reminders for borrowing requests due tomorrow';
    
    private WhatsAppNotificationService $whatsappService;
    
    public function __construct(WhatsAppNotificationService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting H-1 borrowing reminder process...');
        
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        // Find all borrowed items that are due tomorrow and haven't been reminded yet
        $borrowingRequests = BorrowingRequest::where('status', BorrowingRequest::STATUS_BORROWED)
            ->whereDate('return_date', $tomorrow)
            ->whereNull('reminder_sent_at')
            ->with(['user', 'item'])
            ->get();
        
        if ($borrowingRequests->isEmpty()) {
            $this->info('No reminders to send.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$borrowingRequests->count()} borrowing(s) to remind.");
        
        $successCount = 0;
        $failCount = 0;
        
        $this->withProgressBar($borrowingRequests, function ($request) use (&$successCount, &$failCount) {
            try {
                // Send WhatsApp reminder
                $this->whatsappService->notifyReminder($request);
                
                // Mark reminder as sent
                $request->update([
                    'reminder_sent_at' => now(),
                ]);
                
                $successCount++;
                
                Log::info('H-1 reminder sent', [
                    'borrowing_request_id' => $request->id,
                    'student_name' => $request->user->name,
                    'item_name' => $request->item->name,
                    'return_date' => $request->return_date,
                ]);
                
            } catch (\Exception $e) {
                $failCount++;
                
                Log::error('Failed to send H-1 reminder', [
                    'borrowing_request_id' => $request->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });
        
        $this->newLine(2);
        $this->info("Reminder process completed!");
        $this->info("✓ Success: {$successCount}");
        
        if ($failCount > 0) {
            $this->error("✗ Failed: {$failCount}");
        }
        
        return Command::SUCCESS;
    }
}

