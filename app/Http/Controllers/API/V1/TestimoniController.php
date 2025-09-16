<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Enums\Status;
use Illuminate\Http\Request;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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

    public function hasSubmittedTestimonial(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'hasSubmitted' => $user->testimonial()->exists()
        ]);
    }


    public function submitTestimonial(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'rating' => 'required|numeric|min:1|max:5',
        'content' => 'required|string|max:1000',
        'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
    ]);

    // cek apakah user sudah pernah buat testimoni
    $testimoni = $user->testimonial()->first();

    if ($testimoni) {
        if ($testimoni->status === Status::Menunggu) {
            return response()->json([
                'message' => 'Anda sudah mengirim testimoni dan masih menunggu verifikasi.'
            ], 409);
        }

        // update testimoni lama kalau sudah Disetujui atau Ditolak
        if ($request->hasFile('product_photo')) {
            // hapus foto lama kalau ada
            if ($testimoni->product_photo) {
                Storage::disk('public')->delete($testimoni->product_photo);
            }
            $testimoni->product_photo = $request->file('product_photo')->store('testimonials/product-photos', 'public');
        }

        $testimoni->update([
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'status' => Status::Menunggu, // balik ke menunggu setelah edit
        ]);

        return response()->json([
            'message' => 'Testimoni berhasil diperbarui dan menunggu verifikasi.',
            'testimonial' => $this->formatTestimonial($testimoni),
        ], 200);
    }

    // kalau belum ada sama sekali → buat baru
    $productPhotoPath = $request->hasFile('product_photo')
        ? $request->file('product_photo')->store('testimonials/product-photos', 'public')
        : null;

    $testimonial = $user->testimonial()->create([
        'name' => $user->name,
        'avatar' => $user->avatar,
        'rating' => $validated['rating'],
        'content' => $validated['content'],
        'product_photo' => $productPhotoPath,
        'status' => Status::Menunggu
    ]);

    return response()->json([
        'message' => 'Testimoni berhasil dikirim dan menunggu verifikasi.',
        'testimonial' => $this->formatTestimonial($testimonial),
    ], 201);
}

}
