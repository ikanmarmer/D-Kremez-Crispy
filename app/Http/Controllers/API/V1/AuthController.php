<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    /**
     * Helper format user response.
     */
    private function formatUserResponse(User $user): array
    {
        $avatarUrl = $user->avatar ? URL::to(Storage::url($user->avatar)) : null;

        return array_merge(
            $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
            [
                'avatar' => $user->avatar,
                'avatar_url' => $avatarUrl,
            ]
        );
    }

    private function uploadAvatar(Request $request): ?string
    {
        if (!$request->hasFile('avatar')) {
            return null;
        }
        return $request->file('avatar')->store('avatars', 'public');
    }

    private function deleteAvatarFile(?string $avatarPath): void
    {
        if ($avatarPath) {
            Storage::disk('public')->delete($avatarPath);
        }
    }

    /**
     * REGISTER - hanya email, kirim kode verifikasi.
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
            Log::warning('Login gagal', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'time' => now(),
            ]);
            return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 401);
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
            return response()->json(['success' => false, 'message' => 'Profil sudah pernah dilengkapi.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
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
        return response()->json(['success' => true, 'message' => 'Logout berhasil (Semua token dihapus).']);
    }

    /**
     * PROFILE
     */
    public function profile(Request $request)
    {
        return response()->json(['success' => true, 'user' => $this->formatUserResponse($request->user())]);
    }

    /**
     * UPDATE PROFILE
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->only('name', 'email');

        if ($request->hasFile('avatar')) {
            $this->deleteAvatarFile($user->avatar);
            $data['avatar'] = $this->uploadAvatar($request);
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return response()->json(['message' => 'Profil berhasil diperbarui', 'user' => $this->formatUserResponse($user)]);
    }

    /**
     * DELETE AVATAR
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            $this->deleteAvatarFile($user->avatar);
            $user->update(['avatar' => null]);
            return response()->json(['message' => 'Avatar berhasil dihapus']);
        }

        return response()->json(['message' => 'Tidak ada avatar untuk dihapus'], 404);
    }

    /**
     * CHANGE PASSWORD
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}
