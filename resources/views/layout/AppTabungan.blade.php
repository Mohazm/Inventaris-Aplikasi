<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Tabungan') }}</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gray-900 text-white p-4 flex justify-between items-center">
        <a class="text-lg font-bold" href="{{ url('/') }}">{{ config('app.name', 'Tabungan') }}</a>
        <button class="md:hidden text-white" id="menu-toggle">
            
        </button>
        <div class="hidden md:flex space-x-4" id="navbar-menu">
            <a href="{{ route('admin.index') }}" class="hover:text-gray-400">Home</a>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <nav class="w-64 bg-white h-screen p-5 hidden md:block shadow-lg">
            <ul>
                <li class="mb-2">
                    <a href="{{ route('admin.index') }}" class="flex items-center p-2 text-gray-700 hover:bg-gray-200 rounded">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow mt-10 p-4 text-center">
        <span class="text-gray-500">© {{ date('Y') }} {{ config('app.name', 'Tabungan') }}. All rights reserved.</span>
    </footer>

    <!-- Custom JS -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('navbar-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
