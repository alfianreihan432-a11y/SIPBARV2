<?php

namespace App\Jobs;

use App\Models\Classroom;
use App\Models\User;
use App\Services\SipintuService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SyncSipintuUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300; // 5 minutes max for the entire job
    public $backoff = [60, 120, 300]; // Retry delays: 1min, 2min, 5min

    protected bool $forceRefresh;
    protected int $batchSize;
    protected int $chunkSize;

    public function __construct(bool $forceRefresh = false, int $batchSize = 100, int $chunkSize = 200)
    {
        $this->forceRefresh = $forceRefresh;
        $this->batchSize = $batchSize;
        $this->chunkSize = $chunkSize;
    }

    public function handle(SipintuService $sipintu): void
    {
        // Update status to running
        $this->updateStatus('running', 'Memulai sinkronisasi...');

        $startTime = now();
        $stats = [
            'students' => ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0],
            'teachers' => ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0],
        ];

        try {
            // Sync Classrooms first (from student data)
            $this->updateStatus('running', 'Mengambil data siswa...');
            $studentsResult = $sipintu->getStudents(forceRefresh: $this->forceRefresh);

            if ($studentsResult['success']) {
                $students = $studentsResult['data'];
                $stats['students']['fetched'] = count($students);
                $this->updateStatus('running', "Ditemukan {$stats['students']['fetched']} siswa. Memproses...");

                if (!empty($students)) {
                    // Sync classrooms from student data
                    $this->syncClassrooms($students);
                    // Then sync students
                    $this->syncStudents($students, $stats);
                }
            } else {
                $stats['students']['errors']++;
                Log::error('SiPintu sync students failed', ['error' => $studentsResult['error']]);
            }

            // Sync Teachers
            $this->updateStatus('running', 'Mengambil data guru...');
            $teachersResult = $sipintu->getTeachers(forceRefresh: $this->forceRefresh);

            if ($teachersResult['success']) {
                $teachers = $teachersResult['data'];
                $stats['teachers']['fetched'] = count($teachers);
                $this->updateStatus('running', "Ditemukan {$stats['teachers']['fetched']} guru. Memproses...");

                if (!empty($teachers)) {
                    $this->syncTeachers($teachers, $stats);
                }
            } else {
                $stats['teachers']['errors']++;
                Log::error('SiPintu sync teachers failed', ['error' => $teachersResult['error']]);
            }

            $duration = now()->diffInSeconds($startTime);
            $totalCreated = $stats['students']['created'] + $stats['teachers']['created'];
            $totalUpdated = $stats['students']['updated'] + $stats['teachers']['updated'];
            $totalSkipped = $stats['students']['skipped'] + $stats['teachers']['skipped'];
            $totalErrors = $stats['students']['errors'] + $stats['teachers']['errors'];

            $message = "Sinkronisasi selesai: {$totalCreated} user baru, {$totalUpdated} diperbarui, {$totalSkipped} dilewati, {$totalErrors} error. Durasi: {$duration} detik.";

            $this->updateStatus('completed', $message, $stats);
            Log::info('SiPintu sync job completed', ['duration' => $duration, 'stats' => $stats]);

        } catch (\Exception $e) {
            $this->updateStatus('failed', 'Sinkronisasi gagal: ' . $e->getMessage());
            Log::error('SiPintu sync job failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    protected function syncStudents(array $students, array &$stats): void
    {
        // Process in chunks to avoid memory issues
        $chunks = array_chunk($students, $this->chunkSize);

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->updateStatus('running', "Memprosis siswa chunk " . ($chunkIndex + 1) . "/" . count($chunks) . "...");

            foreach ($chunk as $student) {
                try {
                    if (empty($student['nis'] ?? null)) {
                        $stats['students']['skipped']++;
                        continue;
                    }

                    $nis = $student['nis'];

                    // Get classroom_id from classroom data
                    $classroomId = null;
                    if (isset($student['classroom']) && is_array($student['classroom'])) {
                        $className = $student['classroom']['name'] ?? null;
                        if ($className) {
                            $classroom = Classroom::where('name', $className)->first();
                            if ($classroom) {
                                $classroomId = $classroom->id;
                            }
                        }
                    }

                    DB::beginTransaction();

                    $existingUser = User::where('nis', $nis)->first();

                    if ($existingUser) {
                        // Update existing user (don't change password)
                        $existingUser->update([
                            'name' => $student['nama'] ?? $student['name'] ?? $existingUser->name,
                            'kelas' => $student['kelas'] ?? $student['rombel'] ?? $existingUser->kelas,
                            'alamat' => $student['alamat'] ?? $existingUser->alamat,
                            'tanggal_lahir' => !empty($student['tanggal_lahir']) ? $student['tanggal_lahir'] : $existingUser->tanggal_lahir,
                            'jurusan' => $student['jurusan'] ?? $existingUser->jurusan,
                            'classroom_id' => $classroomId ?? $existingUser->classroom_id,
                            'data_source' => 'sipintu',
                            'sipintu_synced_at' => now(),
                        ]);
                        $stats['students']['updated']++;

                        if (!$existingUser->hasRole('Siswa')) {
                            $existingUser->assignRole('Siswa');
                        }
                    } else {
                        // Create new user
                        $newUser = User::create([
                            'name' => $student['nama'] ?? $student['name'] ?? 'Siswa',
                            'email' => $nis . '@sipbar.sch.id',
                            'password' => Hash::make('siswa123'),
                            'nis' => $nis,
                            'kelas' => $student['kelas'] ?? $student['rombel'] ?? null,
                            'alamat' => $student['alamat'] ?? null,
                            'tanggal_lahir' => !empty($student['tanggal_lahir']) ? $student['tanggal_lahir'] : null,
                            'jurusan' => $student['jurusan'] ?? null,
                            'classroom_id' => $classroomId,
                            'data_source' => 'sipintu',
                            'sipintu_synced_at' => now(),
                            'email_verified_at' => now(),
                        ]);
                        $newUser->assignRole('Siswa');
                        $stats['students']['created']++;
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $stats['students']['errors']++;
                    Log::error('SiPintu sync student error', [
                        'data' => $student,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    protected function syncTeachers(array $teachers, array &$stats): void
    {
        // Process in chunks to avoid memory issues
        $chunks = array_chunk($teachers, $this->chunkSize);

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->updateStatus('running', "Memproses guru chunk " . ($chunkIndex + 1) . "/" . count($chunks) . "...");

            foreach ($chunk as $teacher) {
                try {
                    if (empty($teacher['nip'] ?? null)) {
                        $stats['teachers']['skipped']++;
                        continue;
                    }

                    $nip = $teacher['nip'];

                    DB::beginTransaction();

                    $existingUser = User::where('nip', $nip)->first();

                    if ($existingUser) {
                        // Update existing user (don't change password)
                        $existingUser->update([
                            'name' => $teacher['nama'] ?? $teacher['name'] ?? $existingUser->name,
                            'jabatan' => $teacher['jabatan'] ?? $teacher['position'] ?? $existingUser->jabatan,
                            'alamat' => $teacher['alamat'] ?? $existingUser->alamat,
                            'tanggal_lahir' => !empty($teacher['tanggal_lahir']) ? $teacher['tanggal_lahir'] : $existingUser->tanggal_lahir,
                            'phone' => $teacher['no_hp'] ?? $teacher['phone'] ?? $existingUser->phone,
                            'data_source' => 'sipintu',
                            'sipintu_synced_at' => now(),
                        ]);
                        $stats['teachers']['updated']++;

                        if (!$existingUser->hasRole('Guru')) {
                            $existingUser->assignRole('Guru');
                        }
                    } else {
                        // Create new user with DDMMYYYY birthdate email
                        $teacherEmail = User::generateTeacherEmail($nip, !empty($teacher['tanggal_lahir']) ? $teacher['tanggal_lahir'] : null);

                        $newUser = User::create([
                            'name' => $teacher['nama'] ?? $teacher['name'] ?? 'Guru',
                            'email' => $teacherEmail,
                            'password' => Hash::make('guru123'),
                            'nip' => $nip,
                            'jabatan' => $teacher['jabatan'] ?? $teacher['position'] ?? null,
                            'alamat' => $teacher['alamat'] ?? null,
                            'tanggal_lahir' => !empty($teacher['tanggal_lahir']) ? $teacher['tanggal_lahir'] : null,
                            'phone' => $teacher['no_hp'] ?? $teacher['phone'] ?? null,
                            'data_source' => 'sipintu',
                            'sipintu_synced_at' => now(),
                            'email_verified_at' => now(),
                        ]);
                        $newUser->assignRole('Guru');
                        $stats['teachers']['created']++;
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $stats['teachers']['errors']++;
                    Log::error('SiPintu sync teacher error', [
                        'data' => $teacher,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    protected function syncClassrooms(array $students): void
    {
        $classroomsMap = [];

        // Extract unique classrooms from student data
        foreach ($students as $student) {
            if (isset($student['classroom']) && is_array($student['classroom'])) {
                $classroom = $student['classroom'];
                $classroomId = $classroom['id'];
                $className = $classroom['name'] ?? null;

                if ($className && !isset($classroomsMap[$classroomId])) {
                    $classroomsMap[$classroomId] = [
                        'id' => $classroomId,
                        'name' => $className,
                        'kode' => $classroom['kode'] ?? null,
                        'status' => $classroom['status'] ?? 1,
                        'is_pkl' => $classroom['is_pkl'] ?? false,
                    ];
                }
            }
        }

        Log::info('SiPintu sync classrooms extracted', ['count' => count($classroomsMap)]);

        if (empty($classroomsMap)) {
            return;
        }

        $this->updateStatus('running', "Sinkronisasi " . count($classroomsMap) . " kelas...");

        $created = 0;
        $updated = 0;
        $errors = 0;

        foreach ($classroomsMap as $classroomData) {
            try {
                DB::beginTransaction();

                $existingClassroom = Classroom::where('name', $classroomData['name'])->first();

                if ($existingClassroom) {
                    $existingClassroom->update([
                        'kode' => $classroomData['kode'],
                        'status' => $classroomData['status'],
                        'is_pkl' => $classroomData['is_pkl'],
                    ]);
                    $updated++;
                } else {
                    Classroom::create([
                        'name' => $classroomData['name'],
                        'kode' => $classroomData['kode'],
                        'status' => $classroomData['status'],
                        'is_pkl' => $classroomData['is_pkl'],
                    ]);
                    $created++;
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $errors++;
                Log::error('SiPintu sync classroom error', [
                    'data' => $classroomData,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('SiPintu sync classrooms completed', [
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ]);
    }

    protected function updateStatus(string $status, string $message, ?array $stats = null): void
    {
        Cache::put('sipintu_sync_status', [
            'status' => $status,
            'message' => $message,
            'stats' => $stats,
            'updated_at' => now()->toISOString(),
        ], now()->addHours(1));
    }

    public function failed(\Throwable $exception): void
    {
        $this->updateStatus('failed', 'Sinkronisasi gagal: ' . $exception->getMessage());
        Log::error('SiPintu sync job failed permanently', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
