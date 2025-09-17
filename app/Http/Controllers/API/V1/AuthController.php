<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => Role::User,
            'avatar' => $this->uploadAvatar($request),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran pengguna berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->formatUserResponse($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            Log::warning('Login gagal', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'time' => now(),
            ]);
            return response()->json(['message' => 'Email atau kata sandi salah'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Berhasil masuk',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->formatUserResponse($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil keluar']);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => $this->formatUserResponse($request->user()),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
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

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $this->formatUserResponse($user),
        ]);
    }

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

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }

}
