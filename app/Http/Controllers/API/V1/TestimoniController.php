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

        if ($user->testimonial()->exists()) {
            return response()->json([
                'message' => "You have already submitted a testimonial you can't do it again."
            ], 409);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $productPhotoPath = $request->file('product_photo')->store('testimonials/product-photos', 'public');

        $testimonial = $user->testimonial()->create([
            'name' => $user->name,
            'avatar' => $user->avatar,
            'rating' => $validated['rating'],
            'content' => $validated['content'],
            'product_photo' => $productPhotoPath,
            'status' => Status::Menunggu
        ]);

        return response()->json([
            'message' => 'Testimonial submitted successfully. It is now awaiting approval.',
            'testimonial' => $this->formatTestimonial($testimonial),
        ], 201);
    }
}
