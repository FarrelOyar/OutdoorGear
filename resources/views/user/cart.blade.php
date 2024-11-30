@extends('layouts.user')
@section('container')
    <table class="w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-3 px-4 text-left"></th>
                <th class="py-3 px-4 text-left">Produk</th>
                <th class="py-3 px-4 text-left"></th>
                <th class="py-3 px-4 text-left">Price</th>
                <th class="py-3 px-4 text-left">Quantity</th>
                <th class="py-3 px-4 text-left">Total Price</th>
                <th class="py-3 px-4 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($cart as $c)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $i }}</td>
                    <td class="py-3 px-4">
                        <div class="flex justify-center">
                            <img src="storage/foto_barang/{{ $c->barang->foto_barang }}" alt="{{ $c->barang->nama_barang }}"
                                class="w-20 h-20 object-cover">
                        </div>
                    </td>
                    <td class="py-3 px-4">{{ $c->barang->nama_barang }}</td>
                    <td class="py-3 px-4">Rp.{{ $c->barang->harga_barang }}</td>
                    <td class="py-3 px-4 flex items-center">
                        <form action="decrementQTY" method="POST" class="mr-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $c->id }}">
                            <input type="hidden" name="qty" value="{{ $c->qty }}">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 rounded-l-md hover:bg-gray-300 decrement-btn">-</button>
                        </form>

                        <form action="ubahQTY" method="POST" class="mr-2">
                            @csrf
                            <input type="text" name="qty" class="px-4 py-2 bg-gray-200 w-16 text-center qty-input"
                                value="{{ $c->qty }}" min="1">
                            <input type="hidden" name="id" value="{{ $c->id }}">
                        </form>

                        <form action="incrementQTY" method="POST" class="ml-2">
                            @csrf
                            <input type="hidden" name="id" value="{{ $c->id }}">
                            <input type="hidden" name="qty" value="{{ $c->qty }}">
                            <button type="button"
                                class="px-4 py-2 bg-gray-200 rounded-r-md hover:bg-gray-300 increment-btn">+</button>
                        </form>
                        @php
                            $total = number_format(
                                (int) preg_replace('/\./', '', $c->barang->harga_barang) * $c->qty,
                                2,
                            );
                        @endphp
                    <td class="py-3 px-4">Rp.{{ $total }}</td>
                    </td>
                    <td class="py-3 px-4  space">
                        <form class="deleteForm" action="{{ route('deletecart', $c->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table><br>
    <a href="userindex" class=" bg-blue-500 hover:bg-blue-700 p-3 rounded-lg text-white mt-4">Back</button>
        <a href="checkoutform" class=" bg-orange-500 hover:bg-orange-700 p-3 rounded-lg text-white mt-4">Checkout</button>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const qtyInputs = document.querySelectorAll('.qty-input');
                    const decrementBtns = document.querySelectorAll('.decrement-btn');
                    const incrementBtns = document.querySelectorAll('.increment-btn');

                    qtyInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            if (this.value <= 0) {
                                this.value = 1;
                            }
                            this.form.submit();
                        });

                        input.addEventListener('keypress', function(e) {
                            if (e.key === '-') {
                                e.preventDefault();
                            }
                        });
                    });

                    decrementBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const form = this.closest('form');
                            const qtyInput = form.parentElement.querySelector('.qty-input');
                            if (parseInt(qtyInput.value) > 1) {
                                form.submit();
                            }
                        });
                    });

                    incrementBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            this.closest('form').submit();
                        });
                    });
                });
                document.querySelectorAll('.deleteForm').forEach(form => {
                    form.addEventListener('submit', function(event) {
                        event.preventDefault(); 

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
                                this.submit();
                            }
                        });
                    });
                });
            </script>
        @endsection
