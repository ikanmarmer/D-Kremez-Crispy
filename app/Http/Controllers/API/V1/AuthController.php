<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use App\Enums\Role;

class AuthController extends Controller
{
    /**
     * Helper format user.
     */
    private function formatUserResponse(User $user)
    {
        $relativeUrl = $user->avatar ? Storage::url($user->avatar) : null;
        $avatarUrl = $relativeUrl ? URL::to($relativeUrl) : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'avatar_url' => $avatarUrl,
            'email_verified_at' => $user->email_verified_at,
            'profile_completed' => (bool)$user->profile_completed,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    /**
     * REGISTER - Hanya menerima email
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'email' => $request->email,
            'email_verification_code' => $verificationCode,
            'profile_completed' => false,
            'role' => Role::User,
        ]);

        // Kirim email verifikasi dengan SendGrid
        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan cek email Anda untuk kode verifikasi.',
            'user' => $this->formatUserResponse($user),
        ], 201);
    }

    /**
     * VERIFY EMAIL CODE
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $request->code !== $user->email_verification_code) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi salah.',
            ], 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diverifikasi.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'requires_setup' => true,
            'user' => $this->formatUserResponse($user),
        ]);
    }

    /**
     * RESEND VERIFICATION CODE
     */
    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terverifikasi.',
            ], 400);
        }

        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->email_verification_code = $verificationCode;
        $user->save();

        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi baru telah dikirim ke email Anda.',
        ]);
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan verifikasi email Anda terlebih dahulu.',
                'requires_verification' => true,
                'email' => $user->email,
            ], 403);
        }

        if (!$user->profile_completed) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => false,
                'message' => 'Silakan lengkapi profil Anda.',
                'requires_setup' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->formatUserResponse($user),
        ]);
    }

    /**
     * SETUP PROFILE
     */
    public function setupProfile(Request $request)
    {
        $user = $request->user();

        if ($user->profile_completed) {
            return response()->json([
                'success' => false,
                'message' => 'Profil sudah pernah dilengkapi.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->profile_completed = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil dilengkapi.',
            'user' => $this->formatUserResponse($user),
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil (Semua token dihapus).',
        ]);
    }

    /**
     * PROFILE
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $this->formatUserResponse($request->user()),
        ]);
    }
}
