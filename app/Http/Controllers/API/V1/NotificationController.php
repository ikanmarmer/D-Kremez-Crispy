<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get semua notifikasi user
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $notifications = Notification::with('testimonial')
                ->forUser($user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get jumlah notifikasi belum dibaca
    public function unreadCount(Request $request)
    {
        try {
            $user = Auth::user();
            $count = Notification::forUser($user->id)->unread()->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil jumlah notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tandai notifikasi sebagai sudah dibaca
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $notification = Notification::forUser($user->id)->find($id);

            if (!$notification) {
                return response()->json([
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'message' => 'Notifikasi ditandai sebagai sudah dibaca',
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menandai notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tandai semua notifikasi sebagai sudah dibaca
    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            Notification::forUser($user->id)->unread()->update(['dibaca' => true]);

            return response()->json([
                'message' => 'Semua notifikasi ditandai sebagai sudah dibaca'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menandai semua notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Hapus notifikasi
    public function destroy(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $notification = Notification::forUser($user->id)->find($id);

            if (!$notification) {
                return response()->json([
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
