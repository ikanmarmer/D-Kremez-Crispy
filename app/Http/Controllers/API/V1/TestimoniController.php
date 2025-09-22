<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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

    // New method to get the current user's testimonial
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

        if ($testimoni) {
            // Check for approved testimonials
            if ($testimoni->status === Status::Disetujui && !$testimoni->is_notified) {
                $hasNewNotification = true;
                $notificationMessage = 'Selamat! Testimoni Anda telah disetujui. 🎉';
            }
            // Check for rejected testimonials
            elseif ($testimoni->status === Status::Ditolak && !$testimoni->is_notified) {
                $hasNewNotification = true;
                $notificationMessage = 'Testimoni Anda telah ditolak. Silakan periksa dan kirim ulang testimoni Anda. 😔';
            }
            // Check for recently updated pending testimonials
            elseif ($testimoni->status === Status::Menunggu) {
                $lastUpdated = Carbon::parse($testimoni->updated_at);
                if ($lastUpdated->gt(now()->subMinutes(2))) { // edited in the last 2 minutes
                    $hasNewNotification = true;
                    $notificationMessage = 'Testimoni Anda sedang dievaluasi kembali. ⏳';
                }
            }
        }

        return response()->json([
            'hasSubmitted' => $hasSubmitted,
            'hasNewNotification' => $hasNewNotification,
            'notificationMessage' => $notificationMessage,
            'testimonialStatus' => $testimoni->status ?? null,
        ]);
    }
    public function markAsNotified(Request $request)
    {
        $user = $request->user();
        $testimonial = $user->testimonial()->first();

        if ($testimonial && !$testimonial->is_notified) {
            $testimonial->is_notified = true;
            $testimonial->save();

            return response()->json([
                'message' => 'Notification marked as read.',
            ], 200);
        }

        return response()->json([
            'message' => 'No new notification to mark as read.',
        ], 200);
    }
    public function submitTestimonial(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'content' => 'required|string|max:1000',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $testimoni = $user->testimonial()->first();

        if ($testimoni) {
            // Logic to handle an existing testimonial
            if ($request->hasFile('product_photo')) {
                if ($testimoni->product_photo) {
                    Storage::disk('public')->delete($testimoni->product_photo);
                }
                $testimoni->product_photo = $request->file('product_photo')->store('testimonials/product-photos', 'public');
            }

            $statusChanged = $testimoni->status !== Status::Menunggu;

            $testimoni->update([
                'rating' => $validated['rating'],
                'content' => $validated['content'],
                'status' => Status::Menunggu,
                'is_notified' => $statusChanged ? false : $testimoni->is_notified,
            ]);

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

        return response()->json([
            'message' => 'Testimoni berhasil dikirim dan menunggu verifikasi.',
            'testimonial' => $this->formatTestimonial($testimonial),
        ], 201);
    }
}
