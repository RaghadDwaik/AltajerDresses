<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Include Font Awesome CSS for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include Custom CSS -->
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
</head>
<body>
    <div class="registration-section">
        <div class="registration-container">
            <!-- Circular Icon at the Top -->
            <div class="icon-circle">
                <i class="fas fa-user fa-2x"></i>
            </div>
            
            <h2>Register</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="input-field">
                    <i class="fas fa-user input-icon"></i>
                    <input id="name" type="text" name="name" placeholder="Name" required autofocus autocomplete="name" />
                </div>

                <!-- Email -->
                <div class="input-field">
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" name="email" placeholder="Email" required autocomplete="username" />
                </div>

                <!-- Phone Number -->
                <div class="input-field">
                    <i class="fas fa-phone input-icon"></i>
                    <input id="phone-num" type="tel" name="phone-num" placeholder="Phone Number" required autocomplete="phone-num" />
                </div>

                <!-- Address -->
                <div class="input-field">
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input id="address" type="text" name="address" placeholder="Address" required autocomplete="address" />
                </div>

                <!-- Password -->
                <div class="input-field">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" name="password" placeholder="Password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="input-field">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" />
                </div>

                <!-- Register Button -->
                <button type="submit" class="register-btn">Register</button>

                <!-- Already Registered Link -->
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="already-registered">Already registered?</a>
                </div>

                <!-- Google Sign-Up Button -->
                <a href="{{ route('auth.google') }}" class="google-signin-btn">
                    <i class="fab fa-google"></i> Sign Up with Google
                </a>
            </form>
        </div>
    </div>
</body>
</html>
