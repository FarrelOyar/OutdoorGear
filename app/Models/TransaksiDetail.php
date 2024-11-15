<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransaksiDetail
 * 
 * @property int $id
 * @property int $id_transaksi
 * @property int $id_barang
 * @property int $qty
 * @property int $id_denda
 * @property string $total_denda
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Barang $barang
 * @property Denda $denda
 * @property Transaksi $transaksi
 *
 * @package App\Models
 */
class TransaksiDetail extends Model
{
	protected $table = 'transaksi_details';

	protected $casts = [
		'id_transaksi' => 'int',
		'id_barang' => 'int',
		'qty' => 'int',
		'id_denda' => 'int'
	];

	protected $fillable = [
		'id_transaksi',
		'id_barang',
		'qty',
		'id_denda',
		'total_denda'
	];
	

	public function barang()
	{
		return $this->belongsTo(Barang::class, 'id_barang');
	}

	public function denda()
	{
		return $this->belongsTo(Denda::class, 'id_denda');
	}

	public function transaksi()
	{
		return $this->belongsTo(Transaksi::class, 'id_transaksi');
	}
}
