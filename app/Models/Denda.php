<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Denda
 * 
 * @property int $id
 * @property string $tipe_denda
 * @property string $jumlah_denda
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|TransaksiDetail[] $transaksi_details
 *
 * @package App\Models
 */
class Denda extends Model
{
	protected $table = 'dendas';

	protected $fillable = [
		'tipe_denda',
		'jumlah_denda'
	];

	public function transaksi_details()
	{
		return $this->hasMany(TransaksiDetail::class, 'id_denda');
	}
}
