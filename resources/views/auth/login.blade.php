<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    style="background-image:url(storage/aset/auth.jpg); background-repeat: no-repeat; background-size: cover; background-position: center ">
    @if (session('message'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('message') }}"
            });
        </script>
    @endif
    @if (session('loginError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal ',
                text: "{{ session('loginError') }}"
            });
        </script>
    @endif
    <div class="flex justify-center items-center h-svh">
        <div class="p-8 rounded-lg border shadow-xl bg-black bg-opacity-40">
            <h1 class="font-bold text-center text-3xl text-slate-800 ">Login!</h1>
            <form action="/login" method="post" class="">
                @csrf
                <div class="">
                    <label for="email" class="font-bold text-slate-800">Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-white rounded-lg border-blue-500 focus:outline-none border p-[0.5rem]"
                        required><br>
                </div>
                <div class="">
                    <label for="email" class="font-bold text-slate-800">Password:</label>
                    <input type="password" name="password"
                        class="w-full bg-white border rounded-lg border-blue-500 focus:outline-none p-[0.5rem]"
                        required><br>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-700 p-3 rounded-lg text-white mt-4">Login</button>
            </form>
            <p class="text-center text-sm text-white  mt-4 block">Anda Belum punya akun? daftar <a href="/register"
                    class="text-blue-700 hover:underline">disini</a></p>
        </div>
    </div>
</body>

</html>
