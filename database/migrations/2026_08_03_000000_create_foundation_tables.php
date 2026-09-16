<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->bigInteger('expiration')->index();
            });
        }

        if (! Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->bigInteger('expiration')->index();
            });
        }

        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedSmallInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('connection');
                $table->string('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
                $table->index(['connection', 'queue', 'failed_at']);
            });
        }

        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (! empty($tableNames)) {
            if (! Schema::hasTable($tableNames['permissions'])) {
                Schema::create($tableNames['permissions'], function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('guard_name');
                    $table->timestamps();
                    $table->unique(['name', 'guard_name']);
                });
            }

            if (! Schema::hasTable($tableNames['roles'])) {
                Schema::create($tableNames['roles'], function (Blueprint $table) use ($columnNames) {
                    $table->id();
                    $table->unsignedBigInteger($columnNames['team_foreign_key'] ?? 'team_id')->nullable();
                    $table->index($columnNames['team_foreign_key'] ?? 'team_id', 'roles_team_foreign_key_index');
                    $table->string('name');
                    $table->string('guard_name');
                    $table->timestamps();
                    $table->unique([$columnNames['team_foreign_key'] ?? 'team_id', 'name', 'guard_name']);
                });
            }

            if (! Schema::hasTable($tableNames['model_has_permissions'])) {
                Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission) {
                    $table->unsignedBigInteger($pivotPermission);
                    $table->string('model_type');
                    $table->unsignedBigInteger($columnNames['model_morph_key']);
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
                    $table->foreign($pivotPermission)
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->cascadeOnDelete();
                    $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_model_type_primary');
                });
            }

            if (! Schema::hasTable($tableNames['model_has_roles'])) {
                Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole) {
                    $table->unsignedBigInteger($pivotRole);
                    $table->string('model_type');
                    $table->unsignedBigInteger($columnNames['model_morph_key']);
                    $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
                    $table->foreign($pivotRole)
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->cascadeOnDelete();
                    $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_model_type_primary');
                });
            }

            if (! Schema::hasTable($tableNames['role_has_permissions'])) {
                Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
                    $table->unsignedBigInteger($pivotPermission);
                    $table->unsignedBigInteger($pivotRole);
                    $table->foreign($pivotPermission)
                        ->references('id')
                        ->on($tableNames['permissions'])
                        ->cascadeOnDelete();
                    $table->foreign($pivotRole)
                        ->references('id')
                        ->on($tableNames['roles'])
                        ->cascadeOnDelete();
                    $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
                });
            }
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (! Schema::hasTable('jurusans')) {
            Schema::create('jurusans', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->string('kode')->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('kode')->nullable();
                $table->integer('status')->default(1);
                $table->boolean('is_pkl')->default(false);
                $table->timestamps();
                $table->softDeletes();
                $table->unique('name');
            });
        }

        if (! Schema::hasTable('extracurriculars')) {
            Schema::create('extracurriculars', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('kode')->nullable();
                $table->text('description')->nullable();
                $table->string('pembina')->nullable();
                $table->string('pembina_phone')->nullable();
                $table->string('jadwal')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
                $table->unique('name');
            });
        }

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('nis')->nullable();
                $table->string('kelas')->nullable();
                $table->unsignedBigInteger('classroom_id')->nullable();
                $table->string('foto_profil')->nullable();
                $table->text('alamat')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->string('nip')->nullable();
                $table->string('jurusan')->nullable();
                $table->string('jabatan')->nullable();
                $table->string('phone', 20)->nullable();
                $table->unsignedBigInteger('jurusan_id')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
                $table->timestamp('two_factor_confirmed_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->timestamp('sipintu_synced_at')->nullable();
                $table->string('data_source')->default('manual');
                $table->foreign('classroom_id')->references('id')->on('classrooms')->nullOnDelete();
                $table->foreign('jurusan_id')->references('id')->on('jurusans')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('kode');
                $table->unsignedBigInteger('class_leader_id')->nullable();
                $table->unsignedBigInteger('class_advisor_id')->nullable();
                $table->string('class_advisor_phone')->nullable();
                $table->boolean('is_pkl')->default(false);
                $table->integer('status')->default(1);
                $table->timestamps();
                $table->softDeletes();
                $table->unique('name');
                $table->unique('kode');
                $table->foreign('class_leader_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('class_advisor_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->string('color')->default('#2563eb');
                $table->text('description')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index('name');
            });
        }

        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index('name');
            });
        }

        if (! Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('building');
                $table->string('floor')->nullable();
                $table->string('room')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['building', 'floor', 'room']);
            });
        }

        if (! Schema::hasTable('items')) {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable()->unique();
                $table->string('inventory_number')->nullable()->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('brand')->nullable();
                $table->string('type')->nullable();
                $table->year('purchase_year')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->string('condition')->default('Baik');
                $table->string('status')->default('Tersedia');
                $table->integer('stock')->default(1);
                $table->string('photo_path')->nullable();
                $table->string('qr_code')->nullable();
                $table->string('barcode')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['name', 'status']);
            });
        }

        if (! Schema::hasTable('item_images')) {
            Schema::create('item_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('borrowings')) {
            Schema::create('borrowings', function (Blueprint $table) {
                $table->id();
                $table->string('number')->unique();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('borrowed_at');
                $table->date('due_at')->nullable();
                $table->date('returned_at')->nullable();
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['status', 'borrowed_at']);
            });
        }

        if (! Schema::hasTable('borrowing_details')) {
            Schema::create('borrowing_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('borrowing_id')->constrained()->cascadeOnDelete();
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('quantity')->default(1);
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('borrowing_requests')) {
            Schema::create('borrowing_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                $table->integer('quantity')->default(1);
                $table->text('purpose')->nullable();
                $table->date('borrow_date');
                $table->date('return_date');
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'cancelled', 'approved', 'rejected', 'qr_ready', 'borrowed', 'returned', 'overdue'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('borrowed_at')->nullable();
                $table->timestamp('returned_at')->nullable();
                $table->enum('return_condition', ['good', 'damaged', 'lost'])->nullable();
                $table->text('return_notes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('returns')) {
            Schema::create('returns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('borrowing_id')->constrained()->cascadeOnDelete();
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->string('condition')->default('Baik');
                $table->decimal('fine', 10, 2)->default(0);
                $table->text('notes')->nullable();
                $table->string('photo_path')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('maintenances')) {
            Schema::create('maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->date('scheduled_at')->nullable();
                $table->string('status')->default('scheduled');
                $table->timestamps();
                $table->index('scheduled_at');
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->json('data')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'is_read']);
            });
        }

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('action');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->index(['subject_type', 'subject_id']);
            });
        }

        if (! Schema::hasTable('item_returns')) {
            Schema::create('item_returns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('borrowing_request_id')->constrained('borrowing_requests')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('tipe_peminjam', 10)->default('siswa');
                $table->unsignedBigInteger('kajur_id')->nullable();
                $table->enum('kondisi_barang', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
                $table->text('catatan')->nullable();
                $table->string('foto_bukti')->nullable();
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
                $table->text('alasan_ditolak')->nullable();
                $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
                $table->timestamp('tanggal_verifikasi')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('kajur_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('diverifikasi_oleh')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('item_returns');
        Schema::dropIfExists('borrowing_requests');
        Schema::dropIfExists('items');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('users');
        Schema::dropIfExists('extracurriculars');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('jurusans');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};
