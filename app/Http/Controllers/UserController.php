<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Cart;
use App\Models\Denda;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;
use Illuminate\Support\Facades\File as FacadesFile;
use App\Enums\TransactionStatus;


class UserController extends Controller
{
    public function index()
    {
        $denda = Denda::get();
        $transaksi = Transaksi::with('transaksi_details')->where('status', 1)->get();
        $total = 0;
        return view('admin.index', compact('transaksi', 'denda'));
    }
    public function history()
    {
        $denda = Denda::get();
        $transaksi = Transaksi::with('transaksi_details')->where('status', 2)->get();
        $total = 0;
        return view('admin.history', compact('transaksi', 'denda'));
    }
    public function databarang(Request $request)
    {
        $keyword = $request->keyword;

        $kategori = Kategori::get();
        $data_barang = Barang::where('nama_barang', 'like', '%' . $keyword . '%')
            ->orWhereHas('kategori', function ($query) use ($keyword) {
                $query->where('kategori_name', 'like', '%' . $keyword . '%');
            })->paginate(5);
        return view('admin.databarang', compact('data_barang'), compact('kategori'));
    }

    public function datauser()
    {
        $user = DB::table('users')->where('role', 'user')->get();
        return view('admin.datauser', compact('user'));
    }
    public function deleteuser($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $imagePath = public_path('storage/foto_ktp/' . $user->foto_ktp);

            // Hapus user
            if ($user->delete()) {
                // Hapus foto
                if (FacadesFile::exists($imagePath)) {
                    FacadesFile::delete($imagePath);
                }

                DB::commit();
                return redirect('data_user')->with('deleteSuccess', 'User berhasil dihapus');
            }

            DB::rollBack();
            return redirect()->back()
                ->with('deleteError', 'Gagal menghapus user. Silakan coba lagi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('deleteError', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }
    public function tambahbarangprocess(Request $request)
    {
        $request->validate([
            'foto_barang' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,bmp,svg'],
        ], [
            'foto_barang.required' => 'Foto KTP wajib diisi',
            'foto_barang.mimes' => 'Format file tidak valid,Hanya diperbolehkan:jpeg,png,jpg,gif,bmp,svg'
        ]);
        $num = random_int('1111', '9999');

        $exten = $request->file('foto_barang')->getClientOriginalExtension();
        $name = $request->nama_barang . '-' . $num . '.' . $exten;
        $request->file('foto_barang')->storeAs('foto_barang', $name);

        Barang::insert([
            'nama_barang' => $request->nama_barang,
            'harga_barang' => $request->harga_barang,
            'id_kategori' => $request->kategori,
            'foto_barang' => $name,
            'deskripsi' => $request->deskripsi,
            'stock' => $request->stock,
        ]);
        return redirect('data_barang')->with('barangSuccess', 'Barang berhasil ditambahkan');
    }
    public function updatebarang(Request $request)
    {
        // Validasi hanya jika ada file foto yang diupload
        if ($request->hasFile('foto_barang')) {
            $request->validate([
                'foto_barang' => ['file', 'mimes:jpeg,png,jpg,gif,bmp,svg'],
            ], [
                'foto_barang.mimes' => 'Format file tidak valid,Hanya diperbolehkan:jpeg,png,jpg,gif,bmp,svg'
            ]);
        }

        $barang = Barang::findOrFail($request->id);
        $name = $barang->foto_barang; // Menggunakan foto lama sebagai default

        // Proses upload foto baru hanya jika ada file yang diupload
        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama
            $imagePath = public_path('storage/foto_barang/' . $barang->foto_barang);
            if (FacadesFile::exists($imagePath)) {
                FacadesFile::delete($imagePath);
            }

            // Upload foto baru
            $num = random_int('1111', '9999');
            $exten = $request->file('foto_barang')->getClientOriginalExtension();
            $name = $request->nama_barang . '-' . $num . '.' . $exten;
            $request->file('foto_barang')->storeAs('foto_barang', $name);
        }

        // Update data barang
        DB::table('barangs')->where('id', $request->id)->update([
            'nama_barang' => $request->nama_barang,
            'harga_barang' => $request->harga_barang,
            'id_kategori' => $request->kategori,
            'foto_barang' => $name, // Akan menggunakan foto lama jika tidak ada upload baru
            'stock' => $request->stock,
            'barang_keluar' => $request->barang_keluar,
        ]);

        return redirect('data_barang')->with('editbarangSuccess', 'Barang berhasil diedit!');
    }



    public function deletebarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            // Cek apakah barang memiliki transaksi terkait
            if ($barang->transaksiDetails()->exists()) {
                return redirect()->back()
                    ->with('deleteError', 'Barang tidak dapat dihapus karena masih terkait dengan data transaksi');
            }

            // Jika tidak ada transaksi terkait, lanjut hapus
            $imagePath = public_path('storage/foto_barang/' . $barang->foto_barang);
            if (FacadesFile::exists($imagePath)) {
                FacadesFile::delete($imagePath);
            }

            $barang->delete();
            return redirect('data_barang')->with('deleteSuccess', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('deleteError', 'Gagal menghapus barang. Silakan coba lagi.');
        }
    }

    public function userindex(Request $request)
    {
        $keyword = $request->keyword;
        $data_barang = Barang::where('nama_barang', 'like', '%' . $keyword . '%')
            ->orWhereHas('kategori', function ($query) use ($keyword) {
                $query->where('kategori_name', 'like', '%' . $keyword . '%');
            })
            ->paginate(8);
        return view('user.index', compact('data_barang'));
    }
    public function tambahkategori(Request $request)
    {
        Kategori::insert([
            'kategori_name' => $request->nama_kategori
        ]);
        return redirect('data_barang')->with('kategoriSuccess', 'Kategori Berhasil Ditambahkan!');
    }
    public function add_cart(Request $request)
    {

        $cart = Cart::firstOrNew([
            'id_barang' => $request->id_barang,
            'id_user' => $request->id_user,
        ]);
        $cart->qty += 1;
        $cart->save();
        return redirect('userindex')->with('cartSuccess', 'Barang berhasil ditambahkan ke keranjang,silahkan cek keranjang anda!');
    }
    public function cart()
    {
        $cart = Cart::with('barang')->where('id_user', auth()->user()->id)->get();
        return view('user.cart', compact('cart'));
    }
    public function incrementQTY(Request $request)
    {
        $p = $request->qty + 1;
        Cart::where('id', $request->id)->update([
            'qty' => $p
        ]);
        return back();
    }
    public function decrementQTY(Request $request)
    {
        $p = $request->qty - 1;
        Cart::where('id', $request->id)->update([
            'qty' => $p
        ]);
        return back();
    }
    public function ubahQTY(Request $request)
    {
        Cart::where('id', $request->id)->update([
            'qty' => $request->qty
        ]);
        return back();
    }
    public function deletecart($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return back();
    }
    public function checkoutform()
    {
        $cart = Cart::with('barang')->where('id_user', auth()->user()->id)->get();

        $total = 0;

        foreach ($cart as $item) {
            $barang = Barang::find($item->id_barang);
            if ($barang) {
                $harga = (float) str_replace('.', '', $barang->harga_barang);
                $qty = (int) $item->qty;
                $itemTotal = $harga * $qty;
                $total += $itemTotal;
            }
        }

        return view('user.checkout', compact('cart', 'total'));
    }
    public function checkout(Request $request)
    {
        // dd($request->input());
        DB::beginTransaction();

        try {
            $keluar = Carbon::createFromFormat('Y-m-d', $request->tglkeluar);
            $kembali = Carbon::createFromFormat('Y-m-d', $request->tglkembali);
            $selisihHari = $keluar->diffInDays($kembali);
            if ($selisihHari == 0) {
                $selisihHari += 1;
            }
            $total = $request->total_hargacart * $selisihHari;
            $today = now()->format('ymd');

            $lastOrder = Transaksi::whereDate('created_at', today())->latest('id')->first();
            if ($lastOrder) {
                $lastTrackingNumber = $lastOrder->no_resi;
                $numericPart = (int) substr($lastTrackingNumber, -4);
                $nextNumber = str_pad($numericPart + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = '0001';
            }
            $resi = 'RESI' . $today . '-' . auth()->user()->id . '-' . $nextNumber;
            $transaction = Transaksi::create([
                'no_resi' => $resi,
                'id_user' => auth()->user()->id,
                'total' => $total,
                'status' => TransactionStatus::PROSES->value,
                'tanggal_keluar' => $request->tglkeluar,
                'tanggal_kembali' => $request->tglkembali,
            ]);

            // Loop through each item and create a transaction detail
            foreach ($request->items as $item) {
                $barang = Barang::find($item['id_product']);
                $stocksisa = $barang->stock - $barang->barang_keluar;
                // dd($stocksisa);
                if ($stocksisa < $item['qty']) {
                    throw new \Exception("Stock {$barang->nama_barang} tidak mencukupi");
                }

                // Kurangi stock
                $barang->barang_keluar += $item['qty'];
                $barang->save();

                TransaksiDetail::create([
                    'id_transaksi' => $transaction->id,
                    'id_barang' => $item['id_product'],
                    'qty' => $item['qty'],
                    'id_denda' => 1
                ]);
            }
            Cart::where('id_user', auth()->user()->id)->delete();
            // Commit the transaction
            DB::commit();

            return redirect()->route('resi', ['idtransaksi' => $transaction->id]);
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return back()->with('checkoutFail', 'Transaksi Gagal!,Periksa kembali ketersediaan stock!');
        }
    }
    // public function resi(){
    //     $transaksi_detail=TransaksiDetail::where('id_')
    //     return view('user.resi');
    // }
    public function transaksi()
    {
        $transaksi = Transaksi::where('id_user', auth()->user()->id)->with('transaksi_details')->get();
        return view('user.transaksi', compact('transaksi'));
    }
    public function resi($idtransaksi)
    {
        $transaksi = Transaksi::with(['transaksi_details', 'user'])->find($idtransaksi);
        $total = 0;

        foreach ($transaksi->transaksi_details as $item) {
            $barang = Barang::find($item->id_barang);
            if ($barang) {
                $harga = (float) str_replace('.', '', $barang->harga_barang);
                $qty = (int) $item->qty;
                $itemTotal = $harga * $qty;
                $total += $itemTotal;
            }
        }
        return view('user.resi', compact('transaksi', 'total'));
    }
    public function generatepdf($idtransaksi)
    {
        $transaksi = Transaksi::with(['transaksi_details', 'user'])->find($idtransaksi);
        $total = 0;

        foreach ($transaksi->transaksi_details as $item) {
            $barang = Barang::find($item->id_barang);
            if ($barang) {
                $harga = (float) str_replace('.', '', $barang->harga_barang);
                $qty = (int) $item->qty;
                $itemTotal = $harga * $qty;
                $total += $itemTotal;
            }
        }

        $pdf = Pdf::loadView('user.cetakresi', compact('transaksi', 'total'));

        // Konfigurasi PDF
        $pdf->setPaper('F4');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('enable-smart-shrinking', true);

        return $pdf->download('Resine-' . $transaksi->no_resi . '.pdf');
    }
    public function tambahdenda(Request $request)
    {
        Denda::insert([
            'tipe_denda' => $request->jenisdenda,
            'jumlah_denda' => $request->jumlahdenda
        ]);
        return back()->with('dendaSuccess', 'Berhasil ditambahkan!');
    }
    public function transaksiselesai(Request $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            $items = $request->items;
            $totalDenda = 0;

            foreach ($items as $item) {
                // Ambil data denda
                $denda = Denda::find($item['id_denda']);

                // Ambil data barang
                $barang = Barang::find($item['id_barang']);

                // Hitung total denda per item
                $hargaSewa = (float) str_replace('.', '', $barang->harga_barang);
                $dendaPerItem = $hargaSewa * $denda->jumlah_denda * $item['qty'];
                $totalDenda += $dendaPerItem;

                // Update stok barang (kurangi barang_keluar sesuai qty)
                $barang->barang_keluar -= $item['qty'];
                $barang->save();

                // Simpan detail denda ke transaksi_details
                TransaksiDetail::where('id', $item['id_detail'])
                    ->update([
                        'id_denda' => $item['id_denda'],
                        'total_denda' => $dendaPerItem
                    ]);
            }

            // Update status transaksi menjadi selesai dan total denda
            Transaksi::where('id', $request->id_transaksi)
                ->update([
                    'status' => TransactionStatus::SELESAI->value,
                ]);

            DB::commit();
            return redirect()->back()->with('selesaiSuccess', 'Transaksi berhasil diselesaikan');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal di selesaikan!!!');
        }
    }
}
