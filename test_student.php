<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where("nis", "4693")->first();
echo json_encode($u) . "\n\n";

echo "SiPintu Data:\n";
$sipintu = app(\App\Services\SipintuService::class);
$res = $sipintu->getStudents("4693", null, true);
echo json_encode($res, JSON_PRETTY_PRINT) . "\n";
