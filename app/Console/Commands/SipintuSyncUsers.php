<?php

namespace App\Console\Commands;

use App\Models\Classroom;
use App\Models\User;
use App\Services\SipintuService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class SipintuSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipintu:sync-users
                            {--force : Force refresh data from SiPintu (skip cache)}
                            {--batch=100 : Number of records to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data Siswa dan Guru dari SiPintu Gateway ke database lokal SIPBAR';

    protected SipintuService $sipintu;

    public function __construct(SipintuService $sipintu)
    {
        parent::__construct();
        $this->sipintu = $sipintu;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Sinkronisasi Data dari SiPintu Gateway ===');
        $this->newLine();

        $forceRefresh = $this->option('force');
        $batchSize = (int) $this->option('batch');

        // Log start
        Log::info('SiPintu sync started', ['force_refresh' => $forceRefresh, 'batch_size' => $batchSize]);

        $startTime = now();
        $stats = [
            'students' => ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0],
            'teachers' => ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0],
        ];

        // Sync Students
        $this->info('📚 Mengambil data Siswa...');
        $studentsResult = $this->sipintu->getStudents(forceRefresh: $forceRefresh);

        if ($studentsResult['success']) {
            $students = $studentsResult['data'];
            $stats['students']['fetched'] = count($students);
            $this->info("   ✓ {$stats['students']['fetched']} data siswa ditemukan");

            if (!empty($students)) {
                // Sync classrooms first
                $this->syncClassrooms($students);
                // Then sync students
                $this->syncStudents($students, $batchSize, $stats);
            }
        } else {
            $this->error("   ✗ Gagal mengambil data siswa: {$studentsResult['error']}");
            Log::error('SiPintu sync students failed', ['error' => $studentsResult['error']]);
        }

        $this->newLine();

        // Sync Teachers
        $this->info('👨‍🏫 Mengambil data Guru...');
        $teachersResult = $this->sipintu->getTeachers(forceRefresh: $forceRefresh);

        if ($teachersResult['success']) {
            $teachers = $teachersResult['data'];
            $stats['teachers']['fetched'] = count($teachers);
            $this->info("   ✓ {$stats['teachers']['fetched']} data guru ditemukan");

            if (!empty($teachers)) {
                $this->syncTeachers($teachers, $batchSize, $stats);
            }
        } else {
            $this->error("   ✗ Gagal mengambil data guru: {$teachersResult['error']}");
            Log::error('SiPintu sync teachers failed', ['error' => $teachersResult['error']]);
        }

        $this->newLine();

        // Summary
        $duration = now()->diffInSeconds($startTime);
        $this->info('=== Ringkasan Sinkronisasi ===');
        $this->info("Durasi: {$duration} detik");
        $this->newLine();

        $this->info('📚 Siswa:');
        $this->info("   Difetch: {$stats['students']['fetched']}");
        $this->info("   Dibuat:  {$stats['students']['created']}");
        $this->info("   Diupdate: {$stats['students']['updated']}");
        $this->info("   Dilewati: {$stats['students']['skipped']}");
        $this->info("   Error:    {$stats['students']['errors']}");

        $this->newLine();
        $this->info('👨‍🏫 Guru:');
        $this->info("   Difetch: {$stats['teachers']['fetched']}");
        $this->info("   Dibuat:  {$stats['teachers']['created']}");
        $this->info("   Diupdate: {$stats['teachers']['updated']}");
        $this->info("   Dilewati: {$stats['teachers']['skipped']}");
        $this->info("   Error:    {$stats['teachers']['errors']}");

        $this->newLine();

        // Log summary
        Log::info('SiPintu sync completed', [
            'duration' => $duration,
            'stats' => $stats,
        ]);

        $totalCreated = $stats['students']['created'] + $stats['teachers']['created'];
        $totalUpdated = $stats['students']['updated'] + $stats['teachers']['updated'];
        $totalSkipped = $stats['students']['skipped'] + $stats['teachers']['skipped'];
        $totalErrors = $stats['students']['errors'] + $stats['teachers']['errors'];

        if ($totalErrors > 0) {
            $this->warn("⚠ Sinkronisasi selesai dengan {$totalErrors} error. Cek log untuk detail.");
            return Command::FAILURE;
        }

        $this->info("✓ Sinkronisasi selesai: {$totalCreated} user baru, {$totalUpdated} diperbarui, {$totalSkipped} dilewati.");
        return Command::SUCCESS;
    }

    /**
     * Sync students to local database
     */
    protected function syncStudents(array $students, int $batchSize, array &$stats): void
    {
        $this->info('   Memproses data siswa...');

        $bar = $this->output->createProgressBar(count($students));
        $bar->start();

        $studentsToUpsert = [];
        $nisList = [];

        foreach ($students as $student) {
            try {
                // Skip if NIS is empty
                if (empty($student['nis'] ?? null)) {
                    $stats['students']['skipped']++;
                    $bar->advance();
                    continue;
                }

                $nis = $student['nis'];
                $nisList[] = $nis;

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

                // Map SiPintu fields to local users table
                $studentsToUpsert[] = [
                    'name' => $student['nama'] ?? $student['name'] ?? 'Siswa',
                    'email' => $nis . '@sipbar.sch.id',
                    'nis' => $nis,
                    'kelas' => $student['kelas'] ?? $student['rombel'] ?? null,
                    'alamat' => $student['alamat'] ?? null,
                    'tanggal_lahir' => !empty($student['tanggal_lahir']) ? $student['tanggal_lahir'] : null,
                    'jurusan' => $student['jurusan'] ?? null,
                    'classroom_id' => $classroomId,
                    'data_source' => 'sipintu',
                    'sipintu_synced_at' => now(),
                    'updated_at' => now(),
                ];

                $bar->advance();
            } catch (\Exception $e) {
                $stats['students']['errors']++;
                Log::error('SiPintu sync student error', [
                    'data' => $student,
                    'error' => $e->getMessage(),
                ]);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();

        if (empty($studentsToUpsert)) {
            return;
        }

        // Process in batches
        $chunks = array_chunk($studentsToUpsert, $batchSize);

        foreach ($chunks as $chunk) {
            try {
                DB::beginTransaction();

                foreach ($chunk as $userData) {
                    $nis = $userData['nis'];
                    unset($userData['nis']); // Remove NIS from upsert data

                    // Check if user exists
                    $existingUser = User::where('nis', $nis)->first();

                    if ($existingUser) {
                        // Update existing user (don't change password)
                        $existingUser->update($userData);
                        $stats['students']['updated']++;

                        // Assign student role if not already assigned
                        if (!$existingUser->hasRole('Siswa')) {
                            $existingUser->assignRole('Siswa');
                        }
                    } else {
                        // Create new user with default password
                        $userData['password'] = Hash::make('siswa123');
                        $userData['nis'] = $nis;

                        $newUser = User::create($userData);
                        $newUser->assignRole('Siswa');
                        $stats['students']['created']++;
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $stats['students']['errors'] += count($chunk);
                Log::error('SiPintu sync students batch error', [
                    'error' => $e->getMessage(),
                    'batch_size' => count($chunk),
                ]);
            }
        }
    }

    /**
     * Sync teachers to local database
     */
    protected function syncTeachers(array $teachers, int $batchSize, array &$stats): void
    {
        $this->info('   Memproses data guru...');

        $bar = $this->output->createProgressBar(count($teachers));
        $bar->start();

        $teachersToUpsert = [];
        $nipList = [];

        foreach ($teachers as $teacher) {
            try {
                // Skip if NIP is empty
                if (empty($teacher['nip'] ?? null)) {
                    $stats['teachers']['skipped']++;
                    $bar->advance();
                    continue;
                }

                $nip = $teacher['nip'];
                $nipList[] = $nip;

                // Map SiPintu fields to local users table
                $teachersToUpsert[] = [
                    'name' => $teacher['nama'] ?? $teacher['name'] ?? 'Guru',
                    'email' => $nip . '@sipbar.sch.id',
                    'nip' => $nip,
                    'jabatan' => $teacher['jabatan'] ?? $teacher['position'] ?? null,
                    'alamat' => $teacher['alamat'] ?? null,
                    'tanggal_lahir' => !empty($teacher['tanggal_lahir']) ? $teacher['tanggal_lahir'] : null,
                    'phone' => $teacher['no_hp'] ?? $teacher['phone'] ?? null,
                    'data_source' => 'sipintu',
                    'sipintu_synced_at' => now(),
                    'updated_at' => now(),
                ];

                $bar->advance();
            } catch (\Exception $e) {
                $stats['teachers']['errors']++;
                Log::error('SiPintu sync teacher error', [
                    'data' => $teacher,
                    'error' => $e->getMessage(),
                ]);
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine();

        if (empty($teachersToUpsert)) {
            return;
        }

        // Process in batches
        $chunks = array_chunk($teachersToUpsert, $batchSize);

        foreach ($chunks as $chunk) {
            try {
                DB::beginTransaction();

                foreach ($chunk as $userData) {
                    $nip = $userData['nip'];
                    unset($userData['nip']); // Remove NIP from upsert data

                    // Check if user exists
                    $existingUser = User::where('nip', $nip)->first();

                    if ($existingUser) {
                        // Update existing user (don't overwrite password or email)
                        unset($userData['email']);
                        $existingUser->update($userData);
                        $stats['teachers']['updated']++;

                        // Assign guru role if not already assigned
                        if (!$existingUser->hasRole('Guru')) {
                            $existingUser->assignRole('Guru');
                        }
                    } else {
                        // Create new user with DDMMYYYY birthdate email and default password
                        $userData['email'] = User::generateTeacherEmail($nip, $userData['tanggal_lahir'] ?? null);
                        $userData['password'] = Hash::make('guru123');
                        $userData['nip'] = $nip;

                        $newUser = User::create($userData);
                        $newUser->assignRole('Guru');
                        $stats['teachers']['created']++;
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $stats['teachers']['errors'] += count($chunk);
                Log::error('SiPintu sync teachers batch error', [
                    'error' => $e->getMessage(),
                    'batch_size' => count($chunk),
                ]);
            }
        }
    }

    /**
     * Sync classrooms from student data
     */
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

        $this->info("   Sinkronisasi " . count($classroomsMap) . " kelas...");

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
}
