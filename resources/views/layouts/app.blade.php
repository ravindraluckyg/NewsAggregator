<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'News Aggregator')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white p-4 text-center text-lg font-semibold">
        📰 News Aggregator
    </nav>

    <main class="py-6">
        @yield('content')
    </main>

    <footer class="text-center py-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} News Aggregator. All rights reserved.
    </footer>
</body>
</html>
