<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Checking Student Data Structure ===\n\n";

$service = app(\App\Services\SipintuService::class);
$result = $service->getStudents();

if ($result['success'] && !empty($result['data'])) {
    $students = $result['data'];
    echo "Total students: " . count($students) . "\n\n";

    // Count students with classroom_id
    $withClassroomId = 0;
    $withoutClassroomId = 0;
    $withClassroomData = 0;

    foreach ($students as $student) {
        if (isset($student['classroom_id']) && $student['classroom_id'] !== null) {
            $withClassroomId++;
        } else {
            $withoutClassroomId++;
        }
        if (isset($student['classroom']) && $student['classroom'] !== null) {
            $withClassroomData++;
        }
    }

    echo "Students with classroom_id: " . $withClassroomId . "\n";
    echo "Students without classroom_id: " . $withoutClassroomId . "\n";
    echo "Students with classroom data: " . $withClassroomData . "\n\n";

    // Find first student with classroom data
    foreach ($students as $student) {
        if (isset($student['classroom']) && $student['classroom'] !== null) {
            echo "Found student with classroom data:\n";
            echo json_encode($student, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            break;
        }
    }

    // If no classroom data, check if there's a separate classrooms endpoint
    echo "\n=== Testing Classrooms Endpoint ===\n";
    try {
        $response = \Illuminate\Support\Facades\Http::withoutVerifying()
            ->withHeaders([
                'X-Client-ID' => config('sipintu.client_id'),
                'X-Client-Secret' => config('sipintu.client_secret'),
                'Accept' => 'application/json',
            ])
            ->get(config('sipintu.api_url') . '/api/v1/sijuna/classrooms');

        echo "Status: " . $response->status() . "\n";
        echo "Body: " . $response->body() . "\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Failed to get students: " . $result['error'] . "\n";
}
