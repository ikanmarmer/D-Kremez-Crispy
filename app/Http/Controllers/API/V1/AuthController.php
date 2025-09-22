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
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private function makeAvatarUrl(?string $path): ?string
    {
        return $path ? URL::to(Storage::url($path)) : null;
    }

    // public endpoint untuk upload avatar terpisah (route Anda menunjuk ini)
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB limit
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');

        return response()->json([
            'message' => 'Avatar uploaded.',
            'avatar' => $path,
            'avatar_url' => $this->makeAvatarUrl($path),
        ], 201);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $avatarPath = $request->hasFile('avatar')
                ? $request->file('avatar')->store('avatars', 'public')
                : null;

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => Role::User,
                'avatar' => $avatarPath,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();

            return response()->json([
                'message' => 'Pendaftaran pengguna berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user->makeVisible(['avatar'])->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']) + [
                    'avatar' => $user->avatar,
                    'avatar_url' => $this->makeAvatarUrl($user->avatar),
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            // jika file sudah tersimpan dan transaksi gagal, hapus file agar tidak orphan
            if (!empty($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            Log::error('Register failed: ' . $e->getMessage());
            return response()->json(['message' => 'Pendaftaran gagal'], 500);
        }
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
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'avatar_url' => $this->makeAvatarUrl($user->avatar),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['message' => 'Berhasil keluar']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'avatar_url' => $this->makeAvatarUrl($user->avatar),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only('name', 'email');

            if ($request->hasFile('avatar')) {
                // hapus file lama kalau ada
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);
            DB::commit();

            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                    'avatar_url' => $this->makeAvatarUrl($user->avatar),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update profile failed: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal memperbarui profil'], 500);
        }
    }

    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
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
