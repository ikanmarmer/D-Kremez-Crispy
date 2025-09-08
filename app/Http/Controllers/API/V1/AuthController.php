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
use Laravel\Sanctum\PersonalAccessToken;

use App\Enums\Role;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $avatarPath = $this->uploadAvatar($request);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::User,
            'avatar' => $avatarPath
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Check if the user is authenticated via a PersonalAccessToken
        if ($request->user()->currentAccessToken() instanceof PersonalAccessToken) {
            // If so, delete the token
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'Logged out successfully (Token Deleted)'
            ]);
        }

        // If the user is authenticated via session (for web apps)
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully (Session Invalidated)'
        ]);
    }


    public function profile(Request $request)
    {
        $user = $request->user();

        $relativeUrl = $user->avatar ? Storage::url($user->avatar) : null;

        $avatarUrl = $relativeUrl ? URL::to($relativeUrl) : null;

        return response()->json([
            'user' => $user,
            'avatar_url' => $avatarUrl,
        ]);
    }

    // app/Http/Controllers/API/V1/AuthController.php

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get all validated data
        $validatedData = $validator->validated();

        // Check if a new avatar file was provided in the request
        if ($request->hasFile('avatar')) {
            // Delete the old avatar if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Upload the new avatar and get the path
            $avatarPath = $request->file('avatar')->storeAs(
                'avatars',
                time() . '_' . uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension(),
                'public'
            );

            // Add the new avatar path to the validated data
            $validatedData['avatar'] = $avatarPath;
        }

        // Fill the user with all validated data (including the new avatar path if present)
        $user->fill($validatedData);

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    private function uploadAvatar(Request $request)
    {
        if (!$request->hasFile('avatar')) {
            return null;
        }

        $file = $request->file('avatar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('avatars', $filename, 'public');

        return $path;
    }

    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);

            $user->avatar = null;
            $user->save();

            return response()->json([
                'message' => 'Avatar deleted successfully'
            ]);
        }

        return response()->json([
            'message' => 'No avatar to delete'
        ], 404);
    }
}
