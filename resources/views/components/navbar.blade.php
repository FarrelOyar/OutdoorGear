<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index">
            <img src="storage/aset/logo.png" style="border-radius: 50%" alt="" width="55">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" aria-current="page"
                        href="index">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}" aria-current="page"
                        href="history">Riwayat Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('data.barang') ? 'active' : '' }}" href="data_barang">Data
                        Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('data.user') ? 'active' : '' }}" href="data_user">User</a>
                </li>
                <li class="nav-item">
                    <form id="logoutForm" action="/logout" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button id="logoutButton" class="btn">Logout</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
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
