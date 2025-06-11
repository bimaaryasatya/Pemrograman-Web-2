<?php
namespace App\Models;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Cartable
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sku',
        'stock',
        'product_category_id',
        'price',
        'is_active',
        'image_url',
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'product_category_id');
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
