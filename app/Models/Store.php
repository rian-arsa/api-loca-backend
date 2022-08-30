<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'users_id',
        'couriers_id',
        'name',
        'profile',
        'url',
        'image',
        'username',
        'address',
        'deskripsi',
        'catatan_toko',
    ];

    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'store_id', 'id');
    }

    public function bank()
    {
        return $this->hasMany(Bank::class, 'store_id', 'id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'couriers_id', 'id');
    }

    public function day()
    {
        return $this->hasOne(Day::class, 'day_id', 'id');
    }
}
