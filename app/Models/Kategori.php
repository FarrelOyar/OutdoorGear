<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kategori
 * 
 * @property int $id
 * @property string $kategori_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Barang[] $barangs
 *
 * @package App\Models
 */
class Kategori extends Model
{
	protected $table = 'kategoris';

	protected $fillable = [
		'kategori_name'
	];

	public function barangs()
	{
		return $this->hasMany(Barang::class, 'id_kategori');
	}
}
