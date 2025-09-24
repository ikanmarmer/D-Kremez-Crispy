<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TestimoniController extends Controller
{
    private function formatTestimonial(Testimoni $testimoni): array
    {
        $avatarUrl = $testimoni->user->avatar ? URL::to(Storage::url($testimoni->user->avatar)) : null;
        $productPhotoUrl = $testimoni->product_photo ? URL::to(Storage::url($testimoni->product_photo)) : null;

        return [
            'id' => $testimoni->id,
            'rating' => $testimoni->rating,
            'content' => $testimoni->content,
            'status' => $testimoni->status,
            'created_at' => $testimoni->created_at?->toISOString(),
            'updated_at' => $testimoni->updated_at?->toISOString(),
            'user' => [
                'name' => $testimoni->user->name,
                'role' => $testimoni->user->role,
                'avatar_url' => $avatarUrl,
            ],
            'product_photo_url' => $productPhotoUrl,
        ];
    }

    public function getApprovedTestimonials()
    {
        $approvedTestimonials = Testimoni::with('user')
            ->where('status', Status::Disetujui)
            ->get();

        $formattedTestimonials = $approvedTestimonials->map(function ($testimoni) {
            return $this->formatTestimonial($testimoni);
        });

        return response()->json([
            'message' => 'Testimonials successfully retrieved.',
            'testimonials' => $formattedTestimonials,
        ], 200);
    }

    public function getUserTestimonial(Request $request)
    {
        $user = $request->user();
        $testimoni = $user->testimonial()->first();

        if ($testimoni) {
            return response()->json([
                'message' => 'User testimonial retrieved successfully.',
                'testimonial' => $this->formatTestimonial($testimoni),
            ], 200);
        }

        return response()->json([
            'message' => 'No testimonial found for this user.',
        ], 404);
    }

    public function hasSubmittedTestimonial(Request $request)
    {
        $user = $request->user();
        $testimoni = $user->testimonial()->first();

        $hasSubmitted = (bool) $testimoni;
        $hasNewNotification = false;
        $notificationMessage = null;
        $testimonialStatus = null;

        if ($testimoni) {
            $testimonialStatus = $testimoni->status;

            // PERBAIKAN: Cek notifikasi yang belum dibaca untuk testimoni ini
            $unreadNotification = Notification::where('user_id', $user->id)
                ->where('testimonial_id', $testimoni->id)
                ->where('dibaca', false)
                ->latest()
                ->first();

            if ($unreadNotification) {
                $hasNewNotification = true;
                $notificationMessage = $unreadNotification->pesan;
            }
        }

        return response()->json([
            'hasSubmitted' => $hasSubmitted,
            'hasNewNotification' => $hasNewNotification,
            'notificationMessage' => $notificationMessage,
            'testimonial' => $testimoni ? $this->formatTestimonial($testimoni) : null,
            'testimonialStatus' => $testimonialStatus,
        ]);
    }

    public function markAsNotified(Request $request)
    {
        $user = $request->user();
        $testimonial = $user->testimonial()->first();

        if ($testimonial) {
            // PERBAIKAN: Mark semua notifikasi terkait testimoni sebagai dibaca
            Notification::where('user_id', $user->id)
                ->where('testimonial_id', $testimonial->id)
                ->where('dibaca', false)
                ->update(['dibaca' => true]);

            // Juga update is_notified di testimoni
            $testimonial->update(['is_notified' => true]);

            return response()->json([
                'message' => 'Notifications marked as read.',
            ], 200);
        }

        return response()->json([
            'message' => 'No testimonial found.',
        ], 404);
    }

    public function submitTestimonial(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'content' => 'required|string|max:150',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $testimoni = $user->testimonial()->first();

        if ($testimoni) {
            if ($request->hasFile('product_photo')) {
                if ($testimoni->product_photo) {
                    Storage::disk('public')->delete($testimoni->product_photo);
                }
                $testimoni->product_photo = $request->file('product_photo')->store('testimonials/product-photos', 'public');
            }

            $testimoni->update([
                'rating' => $validated['rating'],
                'content' => $validated['content'],
                'status' => Status::Menunggu,
                'is_notified' => false,
            ]);

            // PERBAIKAN: Hapus notifikasi lama dan buat yang baru
            Notification::where('user_id', $user->id)
                ->where('testimonial_id', $testimoni->id)
                ->delete();

            // Buat notifikasi untuk update testimoni
            try {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'testimonial_submitted',
                    'pesan' => 'Testimoni Anda telah berhasil dikirim dan sedang menunggu verifikasi admin.',
                    'testimonial_id' => $testimoni->id,
                    'dibaca' => false,
                    'data' => [
                        'testimonial_content' => substr($validated['content'], 0, 100) . '...',
                        'rating' => $validated['rating'],
                        'action' => 'updated'
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create notification for testimonial update: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Testimoni berhasil diperbarui dan menunggu verifikasi.',
                'testimonial' => $this->formatTestimonial($testimoni),
            ], 200);
        }

        $productPhotoPath = $request->hasFile('product_photo')
            ? $request->file('product_photo')->store('testimonials/product-photos', 'public')
            : null;

        $testimonial = $user->testimonial()->create([
            'name' => $user->name,
            'avatar' => $user->avatar,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'product_photo' => $productPhotoPath,
            'status' => Status::Menunggu,
            'is_notified' => false,
        ]);

        // PERBAIKAN: Buat notifikasi untuk testimoni baru
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'testimonial_submitted',
                'pesan' => 'Testimoni Anda telah berhasil dikirim dan sedang menunggu verifikasi admin.',
                'testimonial_id' => $testimonial->id,
                'dibaca' => false,
                'data' => [
                    'testimonial_content' => substr($validated['content'], 0, 100) . '...',
                    'rating' => $validated['rating'],
                    'action' => 'submitted'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification for new testimonial: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Testimoni berhasil dikirim dan menunggu verifikasi.',
            'testimonial' => $this->formatTestimonial($testimonial),
        ], 201);
    }

    // Method untuk admin menyetujui testimoni (DIPERBAIKI)
    public function approveTestimonial(Request $request, $id)
    {
        try {
            $testimonial = Testimoni::find($id);

            if (!$testimonial) {
                return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
            }

            // Update status testimoni
            $testimonial->update([
                'status' => Status::Disetujui,
                'is_notified' => false
            ]);

            // PERBAIKAN: Hapus notifikasi lama dan buat yang baru
            Notification::where('user_id', $testimonial->user_id)
                ->where('testimonial_id', $testimonial->id)
                ->delete();

            // Buat notifikasi untuk user
            Notification::create([
                'user_id' => $testimonial->user_id,
                'type' => 'testimonial_approved',
                'pesan' => 'Selamat! Testimoni Anda telah disetujui dan dipublikasikan. Terima kasih telah memberikan penilaian kepada kami!',
                'testimonial_id' => $testimonial->id,
                'dibaca' => false,
                'data' => [
                    'testimonial_content' => substr($testimonial->content, 0, 100) . '...',
                    'rating' => $testimonial->rating,
                    'approved_at' => now()->toISOString()
                ]
            ]);

            Log::info('Notification created for approved testimonial', [
                'user_id' => $testimonial->user_id,
                'testimonial_id' => $testimonial->id
            ]);

            return response()->json([
                'message' => 'Testimoni berhasil disetujui',
                'testimonial' => $this->formatTestimonial($testimonial)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to approve testimonial: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menyetujui testimoni',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk admin menolak testimoni (DIPERBAIKI)
    public function rejectTestimonial(Request $request, $id)
    {
        try {
            $testimonial = Testimoni::find($id);

            if (!$testimonial) {
                return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'admin_feedback' => 'required|string|max:255'
            ]);

            // Update status testimoni
            $testimonial->update([
                'status' => Status::Ditolak,
                'admin_feedback' => $validated['admin_feedback'],
                'is_notified' => false
            ]);

            // PERBAIKAN: Hapus notifikasi lama dan buat yang baru
            Notification::where('user_id', $testimonial->user_id)
                ->where('testimonial_id', $testimonial->id)
                ->delete();

            // Buat notifikasi untuk user
            Notification::create([
                'user_id' => $testimonial->user_id,
                'type' => 'testimonial_rejected',
                'pesan' => 'Mohon maaf, testimoni Anda ditolak: ' . $validated['admin_feedback'] . '. Silakan periksa dan kirim ulang testimoni yang sesuai dengan ketentuan.',
                'testimonial_id' => $testimonial->id,
                'dibaca' => false,
                'data' => [
                    'admin_feedback' => $validated['admin_feedback'],
                    'reason' => 'content_violation',
                    'rejected_at' => now()->toISOString()
                ]
            ]);

            Log::info('Notification created for rejected testimonial', [
                'user_id' => $testimonial->user_id,
                'testimonial_id' => $testimonial->id
            ]);

            return response()->json([
                'message' => 'Testimoni berhasil ditolak',
                'testimonial' => $this->formatTestimonial($testimonial)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to reject testimonial: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menolak testimoni',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
