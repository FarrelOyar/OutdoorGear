<?php

use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AutentikasiController::class, 'landingpage'])->name('/')->middleware('guest');
Route::get('/katalog', [AutentikasiController::class, 'katalog'])->name('katalog')->middleware('guest');
Route::get('/login', [AutentikasiController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AutentikasiController::class, 'loginprocess']);
Route::get('/register', [AutentikasiController::class, 'regist'])->name('register')->middleware('guest');
Route::post('/register', [AutentikasiController::class, 'registprocess']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('index');
    Route::get('/history', [UserController::class, 'history'])->name('admin.history');
    Route::get('data_barang', [UserController::class, 'databarang'])->name('data.barang');
    Route::get('data_user', [UserController::class, 'datauser'])->name('data.user');
    Route::post('tambah_barang', [UserController::class, 'tambahbarangprocess'])->name('tambahbarang');
    Route::post('tambah_kategori', [UserController::class, 'tambahkategori']);
    Route::post('edit_barang', [UserController::class, 'updatebarang']);
    Route::delete('/deletebarang/{id}', [UserController::class, 'deletebarang'])->name('deletebarang');
    Route::delete('/deleteuser/{id}', [UserController::class, 'deleteuser'])->name('deleteuser');
    Route::post('tambah_denda', [UserController::class, 'tambahdenda'])->name('tambahdenda');
    Route::post('transaksiselesai', [UserController::class, 'transaksiselesai'])->name('transaksiselesai');
});
Route::post('/logout',[AutentikasiController::class,'logout'])->name('logout');
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/userindex', [UserController::class, 'userindex'])->name('index');
    Route::get('/cart', [UserController::class, 'cart']);
    Route::post('/add_cart', [UserController::class, 'add_cart']);
    Route::post('/incrementQTY', [UserController::class, 'incrementQTY']);
    Route::post('/decrementQTY', [UserController::class, 'decrementQTY']);
    Route::post('/ubahQTY', [UserController::class, 'ubahQTY']);
    Route::delete('/deletecart/{id}', [UserController::class, 'deletecart'])->name('deletecart');
    Route::get('/checkoutform',[UserController::class,'checkoutform']);
    Route::post('/checkout',[UserController::class,'checkout']);
    Route::get('/resi/{idtransaksi}',[UserController::class,'resi'])->name('resi');
    Route::get('/transaksi',[UserController::class,'transaksi'])->name('transaksi');
    Route::get('/print/{idtransaksi}',[UserController::class,'generatepdf'])->name('cetakResi');
});
