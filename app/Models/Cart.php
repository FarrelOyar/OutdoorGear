<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * 
 * @property int $id
 * @property int $id_barang
 * @property int $id_user
 * @property int $qty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Barang $barang
 * @property User $user
 *
 * @package App\Models
 */
class Cart extends Model
{
	protected $table = 'cart';

	protected $casts = [
		'id_barang' => 'int',
		'id_user' => 'int',
		'qty' => 'int'
	];

	protected $fillable = [
		'id_barang',
		'id_user',
		'qty'
	];

	public function barang()
	{
		return $this->belongsTo(Barang::class, 'id_barang');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
