<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Get user notifications
     */
    public function index()
    {
        $notifications = Notifikasi::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notifikasi::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->update(['dibaca' => true]);

        return response()->json([
            'message' => 'Notifikasi ditandai sebagai telah dibaca.'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->update(['dibaca' => true]);

        return response()->json([
            'message' => 'Semua notifikasi ditandai sebagai telah dibaca.'
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $count = Notifikasi::where('user_id', auth()->id())
            ->where('dibaca', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
