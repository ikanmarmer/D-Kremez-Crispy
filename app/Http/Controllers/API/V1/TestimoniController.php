<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimoniController extends Controller
{
    /**
     * Get all approved testimonials (bisa diakses tanpa login)
     */
    public function index()
    {
        $testimonials = Testimoni::with('user')
            ->approved()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($testimonials);
    }

    /**
     * Store a new testimonial
     */
    public function store(Request $request)
    {
        // Cek apakah user sudah pernah memberikan testimoni
        if (Testimoni::userHasTestimonial(auth()->id())) {
            return response()->json([
                'message' => 'Anda sudah pernah memberikan testimoni. Setiap akun hanya boleh memberikan satu testimoni.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'komentar' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|between:1,5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimoni = Testimoni::create([
            'user_id' => auth()->id(),
            'komentar' => $request->komentar,
            'rating' => $request->rating,
            'status' => 'menunggu'
        ]);

        return response()->json([
            'message' => 'Testimoni berhasil dikirim. Menunggu verifikasi admin.',
            'testimoni' => $testimoni
        ], 201);
    }

    /**
     * Get user's testimonial status
     */
    public function userStatus()
    {
        $testimoni = Testimoni::where('user_id', auth()->id())->first();

        if (!$testimoni) {
            return response()->json([
                'has_testimonial' => false
            ]);
        }

        return response()->json([
            'has_testimonial' => true,
            'testimoni' => $testimoni
        ]);
    }
}
