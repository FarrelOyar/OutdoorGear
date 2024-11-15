<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Barang
 * 
 * @property int $id
 * @property string $nama_barang
 * @property string $harga_barang
 * @property int $id_kategori
 * @property string $foto_barang
 * @property string $deskripsi
 * @property int $barang_keluar
 * @property int $stock
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Kategori $kategori
 * @property Collection|Cart[] $carts
 * @property Collection|TransaksiDetail[] $transaksi_details
 *
 * @package App\Models
 */
class Barang extends Model
{
	protected $table = 'barangs';

	protected $casts = [
		'id_kategori' => 'int',
		'barang_keluar' => 'int',
		'stock' => 'int'
	];

	protected $fillable = [
		'nama_barang',
		'harga_barang',
		'id_kategori',
		'foto_barang',
		'deskripsi',
		'barang_keluar',
		'stock'
	];

	public function kategori()
	{
		return $this->belongsTo(Kategori::class, 'id_kategori');
	}

	public function carts()
	{
		return $this->hasMany(Cart::class, 'id_barang');
	}

	public function transaksi_details()
	{
		return $this->hasMany(TransaksiDetail::class, 'id_barang');
	}
}
