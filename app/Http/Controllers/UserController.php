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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;
use Illuminate\Support\Facades\File as FacadesFile;
use App\Enums\TransactionStatus;
use Illuminate\Auth\Events\Validated;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //admin controller
    public function index()
    {
        $denda = Denda::get();
        $transaksi = Transaksi::with('transaksi_details')->where('status', 1)->get();
        $total = 0;
        return view('admin.index', compact('transaksi', 'denda'));
    }
    public function history(Request $request)
    {
        $denda = Denda::get();
        $transaksi = Transaksi::with(['transaksi_details', 'user'])
            ->where('status', 2);

        // Jika ada filter tanggal
        if ($request->tglkeluar && $request->tglkembali) {
            $transaksi->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_keluar', [$request->tglkeluar, $request->tglkembali])
                    ->orWhereBetween('tanggal_kembali', [$request->tglkeluar, $request->tglkembali]);
            });
        }

        $transaksi = $transaksi->get();
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
    public function tambahbarangprocess(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => ['required'],
                'harga_barang' => ['required'],
                'kategori' => ['required'],
                'deskripsi' => ['required'],
                'stock' => ['required'],
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
        } catch (ValidationException $e) {
            return back()->with('validationError', $e->validator->errors()->first());
        }
    }
    public function tambahkategori(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => ['required'],

            ]);
            Kategori::insert([
                'kategori_name' => $request->nama_kategori
            ]);
            return redirect('data_barang')->with('kategoriSuccess', 'Kategori Berhasil Ditambahkan!');
        } catch (ValidationException $e) {
            return back()->with('validationError', $e->validator->errors()->first());
        }
    }
    public function updatebarang(Request $request)
    {
        if ($request->hasFile('foto_barang')) {
            $request->validate([
                'foto_barang' => ['file', 'mimes:jpeg,png,jpg,gif,bmp,svg'],
            ], [
                'foto_barang.mimes' => 'Format file tidak valid,Hanya diperbolehkan:jpeg,png,jpg,gif,bmp,svg'
            ]);
        }

        $barang = Barang::findOrFail($request->id);
        $name = $barang->foto_barang;
        if ($request->hasFile('foto_barang')) {
            $imagePath = public_path('storage/foto_barang/' . $barang->foto_barang);
            if (FacadesFile::exists($imagePath)) {
                FacadesFile::delete($imagePath);
            }
            $num = random_int('1111', '9999');
            $exten = $request->file('foto_barang')->getClientOriginalExtension();
            $name = $request->nama_barang . '-' . $num . '.' . $exten;
            $request->file('foto_barang')->storeAs('foto_barang', $name);
        }
        DB::table('barangs')->where('id', $request->id)->update([
            'nama_barang' => $request->nama_barang,
            'harga_barang' => $request->harga_barang,
            'id_kategori' => $request->kategori,
            'foto_barang' => $name,
            'stock' => $request->stock,
            'barang_keluar' => $request->barang_keluar,
        ]);
        return redirect('data_barang')->with('editbarangSuccess', 'Barang berhasil diedit!');
    }
    public function deleteuser($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $imagePath = public_path('storage/foto_ktp/' . $user->foto_ktp);
            if ($user->delete()) {
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
    public function deletebarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            if ($barang->transaksi_details()->exists()) {
                return redirect()->back()
                    ->with('deleteError', 'Barang tidak dapat dihapus karena masih terkait dengan data transaksi');
            }
            $imagePath = public_path('storage/foto_barang/' . $barang->foto_barang);
            if (FacadesFile::exists($imagePath)) {
                FacadesFile::delete($imagePath);
            }

            $barang->delete();
            return redirect('data_barang')->with('deleteSuccess', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()
                ->with('deleteError', 'Gagal menghapus barang. Silakan coba lagi.');
        }
    }
    public function tambahdenda(Request $request)
    {
        try {
            $validated = $request->validate([
                'jenisdenda' => 'required',
                'jumlahdenda' => 'required'
            ], [
                'jenisdenda.required' => 'Form jenis denda harus di isi!!!',
                'jumlahdenda.required' => 'Form jumlah denda harus di isi!!!'
            ]);

            Denda::insert([
                'tipe_denda' => $request->jenisdenda,
                'jumlah_denda' => $request->jumlahdenda
            ]);
            return back()->with('dendaSuccess', 'Berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return back()->with('validationError', $e->validator->errors()->first());
        }
    }
    public function transaksiselesai(Request $request)
    {
        DB::beginTransaction();
        try {
            $items = $request->items;
            $totalDenda = 0;
            foreach ($items as $item) {
                $denda = Denda::find($item['id_denda']);
                $barang = Barang::find($item['id_barang']);
                $hargaSewa = (float) str_replace('.', '', $barang->harga_barang);
                $dendaPerItem = $hargaSewa * $denda->jumlah_denda * $item['qty'];
                $totalDenda += $dendaPerItem;

                $barang->barang_keluar -= $item['qty'];
                $barang->save();

                TransaksiDetail::where('id', $item['id_detail'])
                    ->update([
                        'id_denda' => $item['id_denda'],
                        'total_denda' => $dendaPerItem
                    ]);
            }

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
    //user controller
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
            foreach ($request->items as $item) {
                $barang = Barang::find($item['id_product']);
                $stocksisa = $barang->stock - $barang->barang_keluar;
                if ($stocksisa < $item['qty']) {
                    throw new \Exception("Stock {$barang->nama_barang} tidak mencukupi");
                }
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
            DB::commit();

            return redirect()->route('resi', ['idtransaksi' => $transaction->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('checkoutFail', 'Transaksi Gagal!,Periksa kembali ketersediaan stock!');
        }
    }
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
}
