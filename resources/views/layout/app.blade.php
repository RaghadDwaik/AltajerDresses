<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altajer Dresses</title>
    <link rel="icon" href="images/dress1.jpeg" type="image/png">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   
</head>
<body>
    @include('layout.header')
    <main>
        @yield('content')
    </main>
    @include('layout.footer')
</body>
</html>
