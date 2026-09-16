<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('borrowing_request_items')) {
            Schema::create('borrowing_request_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('borrowing_request_id')->constrained('borrowing_requests')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->string('kondisi_saat_pinjam')->nullable();
                $table->string('status_pengembalian')->nullable();
                $table->timestamps();

                $table->index('borrowing_request_id');
                $table->index('item_id');
                $table->unique(['borrowing_request_id', 'item_id']);
            });
        }

        if (Schema::hasTable('borrowing_requests') && Schema::hasTable('borrowing_request_items')) {
            $existingRows = DB::table('borrowing_requests')
                ->whereNotNull('item_id')
                ->select('id', 'item_id', 'quantity')
                ->get();

            foreach ($existingRows as $row) {
                $alreadyExists = DB::table('borrowing_request_items')
                    ->where('borrowing_request_id', $row->id)
                    ->where('item_id', $row->item_id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                DB::table('borrowing_request_items')->insert([
                    'borrowing_request_id' => $row->id,
                    'item_id' => $row->item_id,
                    'quantity' => (int) ($row->quantity ?? 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_request_items');
    }
};
