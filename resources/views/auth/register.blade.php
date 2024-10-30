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
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex items-center justify-center">
    <div class="registration-section">
        <div class="registration-container">
            <!-- Circular Icon at the Top -->
            <div class="icon-circle">
                <i class="fas fa-user fa-2x" style="color: #8B4513;"></i>
            </div>
            
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6" style="color: #8B4513;">Register</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="input-field">
                    <label for="name">Name</label>
                    <i class="fas fa-user input-icon"></i>
                    <input id="name" type="text" name="name" required autofocus autocomplete="name" />
                </div>

                <!-- Email -->
                <div class="input-field">
                    <label for="email">Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" name="email" required autocomplete="username" />
                </div>

                <!-- Phone Number -->
                <div class="input-field">
                    <label for="phone-num">Phone Number</label>
                    <i class="fas fa-phone input-icon"></i>
                    <input id="phone-num" type="tel" name="phone-num" required autocomplete="phone-num" />
                </div>

                <!-- Address -->
                <div class="input-field">
                    <label for="address">Address</label>
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <input id="address" type="text" name="address" required autocomplete="address" />
                </div>

                <!-- Password -->
                <div class="input-field">
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="input-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Register Button -->
                <div class="flex justify-center">
                    <button type="submit" class="register-btn">Register</button>
                </div>

                <!-- Already Registered Link -->
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="already-registered">Already registered?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
