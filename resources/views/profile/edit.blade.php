@extends('layout.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}"> <!-- Existing home styles -->
    <link rel="stylesheet" href="profile.css"> <!-- New profile-specific styles -->
</head>
<body>

    <!-- Header Section -->
   

    <!-- Main Content Section -->
    <main class="main-content">
        <div class="container">

            <!-- Profile Section -->
            <section class="profile-section">
                <div class="profile-container">
                    <h2>Edit Profile</h2>

                    <!-- Profile Form -->
                    <form class="profile-form" action="update-profile.php" method="POST" enctype="multipart/form-data">

                        <!-- Profile Image Upload -->
                        <div class="form-group">
                            <label for="profile-image">Profile Photo:</label>
                            <input type="file" id="profile-image" name="profile_image" accept="image/*">
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" value="JohnDoe" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="johndoe@example.com" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="form-group">
                            <label for="phone">Phone Number:</label>
                            <input type="tel" id="phone" name="phone" value="+1234567890" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="update-profile-btn">Update Profile</button>
                    </form>

                    <!-- Logout Section -->
                    <div class="logout-section">
                        <h3>Logout</h3>
                        <p>If you want to log out, click the button below:</p>
                        <form action="{{ route('logout') }}" method="POST">
                        @csrf 
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>

   

</body>
</html>
@endsection
