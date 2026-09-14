<?php

use App\Models\Classroom;
use App\Models\User;
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
        // Populate classroom_id from kelas field for users that have kelas but no classroom_id
        $usersWithKelas = User::whereNull('classroom_id')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->get();

        $updatedCount = 0;
        $notFoundCount = 0;

        foreach ($usersWithKelas as $user) {
            $kelasName = trim($user->kelas);

            // Try to find matching classroom by name
            $classroom = Classroom::where('name', $kelasName)->first();

            if ($classroom) {
                $user->classroom_id = $classroom->id;
                $user->saveQuietly();
                $updatedCount++;
            } else {
                $notFoundCount++;
            }
        }

        // Log the results
        \Log::info('Populate classroom_id migration completed', [
            'total_users_checked' => $usersWithKelas->count(),
            'updated' => $updatedCount,
            'not_found' => $notFoundCount,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set classroom_id back to NULL for users that were populated by this migration
        // This is a safe rollback - it doesn't delete any data, just nullifies the classroom_id
        User::whereNotNull('classroom_id')->update(['classroom_id' => null]);
    }
};
