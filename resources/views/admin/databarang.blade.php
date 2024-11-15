@extends('layouts.admin')
@section('container')
    @if (session('kategoriSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('kategoriSuccess') }}"
            });
        </script>
    @endif
    @if (session('barangSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('barangSuccess') }}"
            });
        </script>
    @endif
    @if (session('editbarangSuccess'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil ',
                text: "{{ session('editbarangSuccess') }}"
            });
        </script>
    @endif
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
    <style>
        .description {
            max-height: 100px;
            /* Batas tinggi untuk tampilan awal */
            overflow: hidden;
            /* Sembunyikan yang melebihi batas */
            transition: max-height 0.3s ease;
            /* Animasi saat diperluas */
        }

        .description.expanded {
            max-height: 500px;
            /* Tinggi saat diperluas */
        }
    </style>

    <button data-bs-toggle="modal" data-bs-target="#tambahbarang" class="btn btn-outline-primary">Tambah Barang</button>
    <button data-bs-toggle="modal" data-bs-target="#tambahkategori" class="btn btn-outline-secondary">Tambah Kategori</button>

    <div class="container">
        <div class="my-3">
            <form action="" method="get">
                <div class="input-group mb-3">
                    <button class="input-group-text btn btn-outline-secondary">Cari</button>
                    <input type="text" class="form-floating" aria-label="Sizing example input"
                        aria-describedby="inputGroup-sizing-sm" name="keyword" id="keyword" placeholder="keyword">
                </div>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Harga Barang</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Foto Barang</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($data_barang as $b)
                    <tr>
                        <th scope="row">{{ $i }}</th>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->harga_barang }}</td>
                        <td>{{ $b->kategori->kategori_name }}</td>
                        <td>
                            <img src="{{ 'storage/foto_barang/' . $b->foto_barang }}" alt=""
                                style="width: 100px; height: auto;">
                        </td>
                        <td>
                            <p class="description" id="description{{ $b->id }}">
                                {{ Str::limit($b->deskripsi, 50) }}
                                <span id="moreText{{ $b->id }}"
                                    style="display: none;">{{ substr($b->deskripsi, 50) }}</span>
                                <a href="#" class="toggleButton text-blue-500 hover:underline"
                                    data-target="{{ $b->id }}">Show More</a>
                            </p>
                        </td>
                        <td>{{ $b->stock }}</td>
                        <td>
                            <button data-bs-toggle="modal" data-bs-target="#editbarang{{ $b->id }}"
                                class="btn btn-outline-info">Edit</button>
                            <form class="deleteForm" action="{{ route('deletebarang', $b->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @php $i++; @endphp
                @endforeach
            </tbody>
        </table>

        <div class="my-5">
            {{ $data_barang->withQueryString()->links() }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggleButton').forEach(button => {
            button.addEventListener('click', function() {
                console.log('Button clicked!'); // Tambahkan ini untuk debugging
                var id = this.getAttribute('data-target');
                var description = document.getElementById('description' + id);
                var moreText = document.getElementById('moreText' + id);

                description.classList.toggle('expanded');

                if (description.classList.contains('expanded')) {
                    moreText.style.display = 'inline';
                    this.textContent = 'Show Less';
                } else {
                    moreText.style.display = 'none';
                    this.textContent = 'Show More';
                }
            });
        });

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

    @foreach ($data_barang as $b)
        <div>
            <div class="modal fade" id="editbarang{{ $b->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Edit Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="edit_barang" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="namabarang">Nama Barang:</label><br>
                                <input type="text" name="nama_barang" id="nama_barang"
                                    value="{{ $b->nama_barang }}"><br>
                                <label for="hargabarang">Harga Barang:</label><br>
                                <input type="text" name="harga_barang" id="harga_barang"
                                    value="{{ $b->harga_barang }}"><br>
                                <label for="kategori">Kategori:</label><br>
                                <select id="kategori" name="kategori" class="">
                                    @foreach ($kategori as $k)
                                        <option value="{{ $k->id }}"
                                            @if ($k->id == $b->id_kategori) selected @endif>{{ $k->kategori_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mb-3">
                                    <label for="fotobarang" class="form-label">Foto Barang:</label>
                                    <input class="form-control" type="file" id="foto_barang" name="foto_barang">
                                </div>
                                <label for="deskripsi">Deskripsi:</label><br>
                                <input type="text" name="deskripsi" id="deskripsi" value="{{ $b->deskripsi }}"><br>
                                <label for="stock">Stock:</label><br>
                                <input type="number" name="stock" id="stock" value="{{ $b->stock }}" min="1"><br>
                                <input type="hidden" name="id" value="{{ $b->id }}">
                                <input type="hidden" name="barang_keluar" value="{{ $b->barang_keluar }}">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" value="Submit" class="btn btn-primary">Edit</button>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="tambahbarang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tambahbarang') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="namabarang">Nama Barang:</label><br>
                        <input type="text" name="nama_barang" id="nama_barang"><br>
                        <label for="hargabarang">Harga Barang:</label><br>
                        <input type="text" name="harga_barang" id="harga_barang"><br>
                        <label for="kategori">Kategori:</label><br>
                        <select id="kategori" name="kategori" class="">
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->kategori_name }}</option>
                            @endforeach
                        </select>
                        <div class="mb-3">
                            <label for="fotobarang" class="form-label">Foto Barang:</label>
                            <input class="form-control" type="file" id="foto_barang" name="foto_barang">
                        </div>
                        <label for="deskripsi">Deskripsi:</label><br>
                        <input type="text" name="deskripsi" id="deskripsi"><br>
                        <label for="stock">Stock:</label><br>
                        <input type="number" name="stock" id="stock" min="1"><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahkategori" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="tambah_kategori" method="POST">
                        @csrf
                        <label for="namabarang">Nama Kategori:</label><br>
                        <input type="text" name="nama_kategori" id="nama_kategori"><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection
