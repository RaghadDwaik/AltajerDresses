<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Include Font Awesome CSS for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Include Custom CSS -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-blue-400 to-purple-500 min-h-screen flex items-center justify-center">
    <div class="login-section">
        <div class="login-container max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
            <div class="icon-circle">
                <i class="fas fa-user fa-2x" style="color: #8B4513;"></i>
            </div>
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6" style="color: #8B4513;">Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="input-field">
                    <label for="email" class="input-label">Email</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input id="email" type="email" name="email" required autofocus autocomplete="username"
                           class="input-box" value="{{ old('email') }}" />
                </div>

                <!-- Password -->
                <div class="input-field">
                    <label for="password" class="input-label">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="input-box" />
                </div>

                <!-- Remember Me and Forgot Password -->
                <div class="flex items-center justify-between mb-4">
                    <a class="footer-link" href="{{ route('password.request') }}">Forgot your password?</a>
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" />
                        <span class="ml-2">Remember me</span>
                    </label>
                </div>

                <!-- Login Button -->
                <div class="flex justify-center">
                    <button type="submit" class="login-btn">Log in</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
