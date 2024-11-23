<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
{
    try {
        $google_user = Socialite::driver('google')->user();

        // Check if the user exists by Google ID
        $user = User::where('google_id', $google_user->getId())->first();

        if (!$user) {
            // Check if the user exists by email
            $user = User::where('email', $google_user->getEmail())->first();

            if (!$user) {
                // Try creating a new user
                try {
                    $user = User::create([
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'phone_num' => '0595442943',
                        'address' => 'ramallah',
                        'email_verified_at' => now(),
                        'password' => bcrypt(Str::random(24)),
                        'google_id' => $google_user->getId(),
                    ]);
                   
                } catch (\Throwable $e) {
                    // Log the error and display the message
                    Log::error('User creation error: ' . $e->getMessage());
                    dd('Error during user creation: ' . $e->getMessage());
                }
            } else {
                // Try updating the existing user
                try {
                    $user->update(['google_id' => $google_user->getId()]);
                } catch (\Throwable $e) {
                    Log::error('User update error: ' . $e->getMessage());
                    dd('Error during user update: ' . $e->getMessage());
                }
            }
        }

        // Log the user in
        Auth::login($user);

        return redirect()->intended('profile.edit');
    } catch (\Throwable $th) {
        Log::error('Google login error: ' . $th->getMessage());
        return redirect()->route('login')->with('error', 'Failed to log in with Google');
    }
}
}