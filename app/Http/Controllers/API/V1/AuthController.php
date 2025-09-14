<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    private function formatUserResponse(User $user)
    {
        $avatarUrl = $user->avatar ? URL::to(Storage::url($user->avatar)) : null;

        return array_merge(
            $user->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']),
            ['avatar' => $user->avatar, 'avatar_url' => $avatarUrl]
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => Role::User,
            'avatar'   => $this->uploadAvatar($request),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'User registered successfully',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $this->formatUserResponse($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $this->formatUserResponse($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
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
            'name'     => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $data = $request->only('name', 'email');

        if ($request->hasFile('avatar')) {
            $this->deleteAvatarFile($user->avatar);
            $data['avatar'] = $this->uploadAvatar($request);
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $this->formatUserResponse($user),
        ]);
    }

    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            $this->deleteAvatarFile($user->avatar);
            $user->update(['avatar' => null]);

            return response()->json(['message' => 'Avatar deleted successfully']);
        }

        return response()->json(['message' => 'No avatar to delete'], 404);
    }
}
