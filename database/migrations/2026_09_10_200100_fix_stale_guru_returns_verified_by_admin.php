<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Fix stale guru ItemReturn records that were incorrectly approved by Admin.
 * 
 * For records where tipe_peminjam='guru' and diverifikasi_oleh points to an admin:
 * - If status='disetujui' by admin → the BorrowingRequest.checkin_by was already set (by Admin).
 *   We leave the BorrowingRequest as-is (data already processed), but correct the ItemReturn
 *   metadata so historical queries make sense.
 * 
 * For records where tipe_peminjam='guru' and status='menunggu':
 * - These will now route correctly to Kajur via the new filter.
 */
return new class extends Migration
{
    public function up(): void
    {        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        // Use Spatie permission tables to find admin users
        DB::statement("
            UPDATE item_returns ir
            INNER JOIN model_has_roles mhr ON mhr.model_id = ir.diverifikasi_oleh
            INNER JOIN roles r ON r.id = mhr.role_id AND r.name = 'admin'
            SET ir.catatan = CONCAT(
                COALESCE(ir.catatan, ''),
                CASE WHEN ir.catatan IS NOT NULL AND ir.catatan != '' THEN ' | ' ELSE '' END,
                '[CATATAN SISTEM: Pengembalian ini sebelumnya diverifikasi Admin - seharusnya melalui Kepala Jurusan]'
            )
            WHERE ir.tipe_peminjam = 'guru'
              AND ir.status = 'disetujui'
              AND mhr.model_type = 'App\\\\Models\\\\User'
        ");
    }

    public function down(): void
    {
        // Not reversible in a meaningful way
    }
};
