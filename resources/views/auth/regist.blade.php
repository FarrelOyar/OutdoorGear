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
    <div class="flex justify-center items-center h-svh">
        <div class="p-8 rounded-lg border shadow-xl bg-black bg-opacity-40">
            <h1 class="font-bold text-center text-3xl text-slate-800 ">Regist!</h1>

            <form action="/register" method="post" enctype="multipart/form-data">
                @csrf
                <label for="username" class="font-bold text-gray-300 text-sm ">Username:</label><br>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full mb-2 bg-white border rounded-lg border-gray-500 focus:outline-none p-[0.1rem]"
                    required><br>
                @if ($errors->has('username'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('username') }}"
                        });
                    </script>
                @endif
                <label for="email" class="font-bold text-gray-300 text-sm">Email:</label><br>
                <input type="text" name="email" value="{{ old('email') }}"
                    class="w-full mb-2 bg-white border rounded-lg border-gray-500 focus:outline-none p-[0.1rem]"
                    required><br>
                @if ($errors->has('email'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('email') }}"
                        });
                    </script>
                @endif
                <label for="username" class="font-bold text-gray-300 text-sm">Password:</label><br>
                <input type="password" name="password1" value="{{ old('password1') }}"
                    class="w-full mb-2 bg-white border rounded-lg border-gray-500 focus:outline-none p-[0.1rem]"
                    required><br>
                @if ($errors->has('password1'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('password1') }}"
                        });
                    </script>
                @endif
                <label for="username" class="font-bold text-gray-300 text-sm">Masukkan kembali password:</label><br>
                <input type="password" name="password2" value="{{ old('password2') }}"
                    class="w-full mb-2 bg-white border rounded-lg border-gray-500 focus:outline-none p-[0.1rem]"
                    required><br>
                @if ($errors->has('password2'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('password2') }}"
                        });
                    </script>
                @endif
                <label for="username" class="font-bold text-gray-300 text-sm">NIK:</label><br>
                <input type="text" name="nik" value="{{ old('nik') }}"
                    class="w-full mb-2 bg-white border rounded-lg border-gray-500 focus:outline-none p-[0.1rem]"
                    required><br>
                @if ($errors->has('nik'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('nik') }}"
                        });
                    </script>
                @endif
                <label for="username" class="font-bold text-gray-300 text-sm">Foto KTP:</label><br>
                <input type="file" name="foto_ktp" value="{{ old('foto_ktp') }}"
                    class="block w-full text-sm border border-gray-500 rounded-sm cursor-pointer bg-gray-200 dark:text-gray-400 focus:outline-none dark:bg-white dark:border-brown-600 dark:placeholder-blue-400"
                    required><br>
                @if ($errors->has('foto_ktp'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "{{ $errors->first('foto_ktp') }}"
                        });
                    </script>
                @endif
                <button type="submit"
                    class="w-full p-1 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded">Daftar</button>
            </form>
            <a href="../" class="text-blue-800 hover:text-blue-500 hover:underline">Kembali</a>
        </div>
    </div>
</body>

</html>
