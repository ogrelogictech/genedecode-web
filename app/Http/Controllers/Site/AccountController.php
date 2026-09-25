<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
<<<<<<< Updated upstream
=======
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
>>>>>>> Stashed changes

class AccountController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Your profile has been updated successfully.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success'  => true,
            'message'  => 'Your password has been changed successfully. Please log in again.',
            'redirect' => route('login'), 
        ]);
    }
}