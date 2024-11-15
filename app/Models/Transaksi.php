<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatus;

/**
 * Class Transaksi
 * 
 * @property int $id
 * @property string $no_resi
 * @property int $id_user
 * @property string $total
 * @property int $status
 * @property Carbon $tanggal_keluar
 * @property Carbon $tanggal_kembali
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|TransaksiDetail[] $transaksi_details
 *
 * @package App\Models
 */
class Transaksi extends Model
{
	protected $table = 'transaksis';

	protected $casts = [
		'id_user' => 'int',
        'status' => TransactionStatus::class,
		'tanggal_keluar' => 'datetime',
		'tanggal_kembali' => 'datetime'
	];

	protected $fillable = [
		'no_resi',
		'id_user',
		'total',
		'status',
		'tanggal_keluar',
		'tanggal_kembali'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function transaksi_details()
	{
		return $this->hasMany(TransaksiDetail::class, 'id_transaksi');
	}
}
