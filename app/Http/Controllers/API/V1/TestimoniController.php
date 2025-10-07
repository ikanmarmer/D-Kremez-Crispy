<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TestimoniController extends Controller
{
    private function formatTestimonial(Testimoni $testimoni): array
{
    // ⚠️ PERBAIKAN: Selalu ambil avatar terbaru dari user, bukan dari testimoni
    $avatarUrl = $testimoni->user->avatar ? URL::to(Storage::url($testimoni->user->avatar)) : null;
    $productPhotoUrl = $testimoni->product_photo ? URL::to(Storage::url($testimoni->product_photo)) : null;

    return [
        'id' => $testimoni->id,
        'rating' => (float) $testimoni->rating,
        'content' => $testimoni->content,
        'status' => $testimoni->status,
        'created_at' => $testimoni->created_at?->toISOString(),
        'updated_at' => $testimoni->updated_at?->toISOString(),
        'user' => [
            'id' => $testimoni->user->id, // Pastikan ID user ada
            'name' => $testimoni->user->name, // Nama terbaru dari user
            'role' => $testimoni->user->role ?? 'user',
            'avatar_url' => $avatarUrl, // ⭐ SELALU avatar terbaru
        ],
        'product_photo_url' => $productPhotoUrl,
    ];
}

    public function getApprovedTestimonials()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error getting approved testimonials: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve testimonials.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserTestimonial(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $testimoni = Testimoni::where('user_id', $user->id)->first();

            if ($testimoni) {
                return response()->json([
                    'message' => 'User testimonial retrieved successfully.',
                    'testimonial' => $this->formatTestimonial($testimoni),
                ], 200);
            }

            return response()->json([
                'message' => 'No testimonial found for this user.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting user testimonial: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve user testimonial.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkTestimonialStatus(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'hasTestimonial' => false,
                    'hasNewNotification' => false,
                    'notificationMessage' => null,
                    'testimonial' => null,
                    'testimonialStatus' => null,
                ], 200);
            }

            $testimoni = Testimoni::where('user_id', $user->id)->first();

            $hasTestimonial = (bool) $testimoni;
            $hasNewNotification = false;
            $notificationMessage = null;
            $testimonialStatus = null;

            if ($testimoni) {
                $testimonialStatus = $testimoni->status;

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
                'hasTestimonial' => $hasTestimonial,
                'hasNewNotification' => $hasNewNotification,
                'notificationMessage' => $notificationMessage,
                'testimonial' => $testimoni ? $this->formatTestimonial($testimoni) : null,
                'testimonialStatus' => $testimonialStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking testimonial status: ' . $e->getMessage());
            return response()->json([
                'hasTestimonial' => false,
                'hasNewNotification' => false,
                'notificationMessage' => null,
                'testimonial' => null,
                'testimonialStatus' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsNotified(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated.',
                ], 401);
            }

            $testimonial = Testimoni::where('user_id', $user->id)->first();

            if ($testimonial) {
                Notification::where('user_id', $user->id)
                    ->where('testimonial_id', $testimonial->id)
                    ->where('dibaca', false)
                    ->update(['dibaca' => true]);

                $testimonial->update(['is_notified' => true]);

                return response()->json([
                    'message' => 'Notifications marked as read.',
                ], 200);
            }

            return response()->json([
                'message' => 'No testimonial found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error marking as notified: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to mark notifications as read.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function submitTestimonial(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $validated = $request->validate([
                'rating' => 'required|numeric|min:1|max:5',
                'content' => 'required|string|max:150',
                'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $testimoni = Testimoni::where('user_id', $user->id)->first();

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
                    // DIHAPUS: 'admin_feedback' => null,
                ]);

                Notification::where('user_id', $user->id)
                    ->where('testimonial_id', $testimoni->id)
                    ->delete();

                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'testimonial_submitted',
                        'pesan' => 'Testimoni Anda telah berhasil dikirim dan sedang menunggu verifikasi admin.',
                        'testimonial_id' => $testimoni->id,
                        'dibaca' => false,
                        'data' => [
                            'testimonial_content' => Str::limit($validated['content'], 100),
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

            $testimonial = Testimoni::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'rating' => $validated['rating'],
                'content' => $validated['content'],
                'product_photo' => $productPhotoPath,
                'status' => Status::Menunggu,
                'is_notified' => false,
                // DIHAPUS: 'admin_feedback' => null,
            ]);

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing testimonial: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Terjadi kesalahan saat menyimpan testimoni: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTestimonial(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $validated = $request->validate([
                'rating' => 'required|numeric|min:1|max:5',
                'content' => 'required|string|max:150',
                'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $testimoni = Testimoni::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$testimoni) {
                return response()->json([
                    'error' => 'Not found',
                    'message' => 'Testimoni tidak ditemukan'
                ], 404);
            }

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

            Notification::where('user_id', $user->id)
                ->where('testimonial_id', $testimoni->id)
                ->delete();

            try {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'testimonial_submitted',
                    'pesan' => 'Testimoni Anda telah berhasil diperbarui dan sedang menunggu verifikasi admin.',
                    'testimonial_id' => $testimoni->id,
                    'dibaca' => false,
                    'data' => [
                        'testimonial_content' => Str::limit($validated['content'], 100),
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating testimonial: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Terjadi kesalahan saat memperbarui testimoni: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTestimonial(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            $testimoni = Testimoni::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$testimoni) {
                return response()->json([
                    'error' => 'Not found',
                    'message' => 'Testimoni tidak ditemukan'
                ], 404);
            }

            // Hapus gambar jika ada
            if ($testimoni->product_photo) {
                Storage::disk('public')->delete($testimoni->product_photo);
            }

            // Hapus notifikasi terkait
            Notification::where('testimonial_id', $testimoni->id)->delete();

            $testimoni->delete();

            return response()->json([
                'message' => 'Testimoni berhasil dihapus.',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting testimonial: ' . $e->getMessage());
            return response()->json([
                'error' => 'Internal server error',
                'message' => 'Terjadi kesalahan saat menghapus testimoni: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approveTestimonial(Request $request, $id)
    {
        try {
            $testimonial = Testimoni::find($id);

            if (!$testimonial) {
                return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
            }

            $testimonial->update([
                'status' => Status::Disetujui,
                'is_notified' => false,
                // DIHAPUS: 'admin_feedback' => null,
            ]);

            Notification::where('user_id', $testimonial->user_id)
                ->where('testimonial_id', $testimonial->id)
                ->delete();

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

    public function rejectTestimonial(Request $request, $id)
    {
        try {
            $testimonial = Testimoni::find($id);

            if (!$testimonial) {
                return response()->json(['message' => 'Testimoni tidak ditemukan'], 404);
            }

            // DIHAPUS: Validasi admin_feedback karena kolom tidak ada
            // $validated = $request->validate([
            //     'admin_feedback' => 'required|string|max:255'
            // ]);

            $testimonial->update([
                'status' => Status::Ditolak,
                // DIHAPUS: 'admin_feedback' => $validated['admin_feedback'],
                'is_notified' => false
            ]);

            Notification::where('user_id', $testimonial->user_id)
                ->where('testimonial_id', $testimonial->id)
                ->delete();

            Notification::create([
                'user_id' => $testimonial->user_id,
                'type' => 'testimonial_rejected',
                'pesan' => 'Mohon maaf, testimoni Anda ditolak. Silakan periksa dan kirim ulang testimoni yang sesuai dengan ketentuan.',
                'testimonial_id' => $testimonial->id,
                'dibaca' => false,
                'data' => [
                    // DIHAPUS: 'admin_feedback' => $validated['admin_feedback'],
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
