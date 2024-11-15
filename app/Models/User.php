<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * 
 * @property int $id
 * @property string $role
 * @property string $email
 * @property string $nik
 * @property string $username
 * @property string $password
 * @property string $foto_ktp
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Cart[] $carts
 * @property Collection|Transaksi[] $transaksis
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'role',
        'email',
        'nik',
        'username',
        'password',
        'foto_ktp'
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'id_user');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }
}
