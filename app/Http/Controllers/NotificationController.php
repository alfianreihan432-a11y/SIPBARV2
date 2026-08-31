<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get list of notifications for the authenticated user, filtered by category.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $category = $request->query('category', 'notification');

        $query = Notification::where('user_id', $userId)
            ->where('category', $category);

        $unreadCount = (clone $query)->where('is_read', false)->count();
        $notifications = $query->latest()->limit(20)->get();

        return response()->json([
            'success' => true,
            'category' => $category,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Mark all notifications of a given category as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $category = $request->input('category', 'notification');

        Notification::where('user_id', Auth::id())
            ->where('category', $category)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai telah dibaca.',
        ]);
    }
}
