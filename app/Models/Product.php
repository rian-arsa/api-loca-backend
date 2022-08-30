<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'users_id',
        'deskripsi',
        'price',
        'stok',
        'berat',
        'panjang',
        'lebar',
        'tinggi',
        'status',
        'category',
        'categories_id',
        'couriers_id',
    ];

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'products_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'categories_id', 'id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'couriers_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'products_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'products_id', 'id');
    }
}
