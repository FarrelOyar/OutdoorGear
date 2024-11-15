<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Outdoorgear</title>
</head>

<body class="bg-slate-300">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <label class="text-white font-bold flex items-center">
                <img src="{{asset('storage/aset/logo.png')}}" alt="" class="w-12 h-12 rounded-full mr-2">
                Outdoorgear
            </label>
        </div>  
    </nav>
    <script>
        document.getElementById('logoutButton').addEventListener('click', function() {
            if (confirm('Yakin Logout?')) {
                document.getElementById('logoutForm').submit();
            }
        });
    </script>
        @yield('container')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>

</html>
