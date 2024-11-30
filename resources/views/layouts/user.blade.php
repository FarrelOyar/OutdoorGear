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
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/userindex" class="text-white font-bold flex items-center">
                <img src="storage/aset/logo.png" alt="" class="w-12 h-12 rounded-full mr-2">
                Outdoorgear
            </a>
            <ul class="flex space-x-4 items-center">

                <li class="relative">
                    <button id="dropdownButton" class="text-white flex items-center focus:outline-none">
                        <span class="mr-2"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </span>

                    </button>
                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 transform transition-all duration-300 ease-out scale-95 z-10">
                        <div class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>


                                <a href="/" class="flex">Home</a>
                            </div>
                        </div>
                        <div class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <form id="logoutForm" action="/logout" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                <button id="logoutButton">Logout</button>
                            </div>
                        </div>
                        <div class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <a href="cart" class="flex">Keranjang</a>
                            </div>
                        </div>
                        <div class="block px-4 py-2 text-gray-800 hover:bg-gray-200">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>

                                <a href="transaksi" class="flex">Transaksi</a>
                            </div>
                        </div>
                </li>
        </div>
        </ul>
        </div>
    </nav>

    <script>
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
            dropdownMenu.classList.toggle('opacity-100');
            dropdownMenu.classList.toggle('scale-100');
            dropdownMenu.classList.toggle('scale-95');
        });

        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
                dropdownMenu.classList.remove('opacity-100');
                dropdownMenu.classList.add('opacity-0');
                dropdownMenu.classList.remove('scale-100');
                dropdownMenu.classList.add('scale-95');
            }
        });

        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan keluar dari aplikasi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>

    @yield('container')
</body>

</html>
