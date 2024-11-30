<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OutdoorGear</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <nav class="bg-white shadow-lg fixed w-full z-10">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4">
                            <span class="font-bold text-2xl text-blue-600">OutdoorGear</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="/login"
                        class="py-2 px-4 border border-blue-500 text-blue-500 rounded hover:bg-blue-500 hover:text-white transition duration-300">Login</a>
                    <a href="/register"
                        class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-500 transition duration-300">Daftar</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="pt-16">
        @yield('guest')
    </div>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">OutdoorGear</h3>
                    <p class="text-gray-400">Solusi penyewaan peralatan outdoor terpercaya</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Layanan</h4>
                    <ul class="text-gray-400">
                        <li class="mb-2"><a href="/login" class="hover:text-white">Sewa Peralatan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Hubungi Kami</h4>
                    <ul class="text-gray-400">
                        <li class="mb-2">📱 +62 234-567-890</li>
                        <li class="mb-2">📧 OutdoorGear@rent.com</li>
                        <li class="mb-2">📍 Jl. Gunung Kembar No. 123, Malang</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 OutdoorGear. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
