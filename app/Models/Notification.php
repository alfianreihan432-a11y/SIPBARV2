<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function sendToUser(int $userId, string $type, string $message, ?array $data = null): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
            'data' => $data,
        ]);
    }

    public static function sendToAdmins(string $type, string $message, ?array $data = null): void
    {
        $admins = User::role(['admin', 'super-admin', 'petugas'])->get();
        foreach ($admins as $admin) {
            self::create([
                'user_id' => $admin->id,
                'type' => $type,
                'message' => $message,
                'is_read' => false,
                'data' => $data,
            ]);
        }
    }
}
