<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Login Restriction Mode (Mode Pembatasan / Maintenance Login Siswa)
    |--------------------------------------------------------------------------
    |
    | Pengaturan untuk membatasi login pada role siswa:
    | - enabled: true untuk mengaktifkan pembatasan, false untuk membuka akses semua siswa.
    | - whitelisted_students: daftar email atau NIS siswa yang tetap diizinkan login.
    | - rejection_message: pesan penolakan khusus untuk siswa di luar whitelist.
    |
    */
    'login_restriction' => [
        'enabled' => env('LOGIN_RESTRICTION_MODE', true),

        'whitelisted_students' => array_values(array_filter(
            array_map('trim', explode(',', (string) env(
                'STUDENT_LOGIN_WHITELIST',
                '4714@sipbar.sch.id,webdev@sipbar.sch.id,basket@sipbar.sch.id,pmr@sipbar.sch.id'
            )))
        )),

        'rejection_message' => 'Akses sementara dibatasi. Silakan hubungi admin sekolah untuk informasi lebih lanjut.',
    ],

];
