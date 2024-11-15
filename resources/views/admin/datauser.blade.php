@extends('layouts.admin')
@section('container')
    @if (session('deleteSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('deleteSuccess') }}"
            });
        </script>
    @endif
    @if (session('deleteError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal! ',
                text: "{{ session('deleteError') }}"
            });
        </script>
    @endif
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Email</th>
                    <th scope="col">NIK</th>
                    <th scope="col">Username</th>
                    <th scope="col">Foto KTP</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($user as $b)
                    <tr>
                        <th scope="row">{{ $i }}</th>
                        <td>{{ $b->email }}</td>
                        <td>{{ $b->nik }}</td>
                        <td>{{ $b->username }}</td>
                        <td><img src="{{ 'storage/foto_ktp/' . $b->foto_ktp }}" alt=""
                                style="width: 100px; height: auto;"></td>
                        <td>
                            <form class="deleteForm" action="{{ route('deleteuser', $b->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        document.querySelectorAll('.deleteForm').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah form submit secara langsung

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form setelah konfirmasi
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
