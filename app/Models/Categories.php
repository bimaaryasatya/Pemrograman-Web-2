<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

class Categories
{
    private static $product_categories = [
        [
            'id'=> 1,
            'name' => 'Pria',
            'slug' => 'Pakaian Pria',
            'description' => 'Ini adalah produk pakaian pria',
            'image' => 'https://example.com/image1.jpg',
        ],
        
        [
            'id'=> 2,
            'name' => 'Wanita',
            'slug' => 'Pakaian Wanita',
            'description' => 'Ini adalah produk pakaian wanita',
            'image' =>  'https://placehold.co/300x300?text=Pakaian+Wanita',
        ],
        
        [
            'id'=> 3,
            'name' => 'Anak-Anak',
            'slug' => 'Pakaian Anak-Anak',
            'description' => 'Ini adalah produk pakaian anak-anak',
            'image' => 'https://placehold.co/300x300?text=Pakaian+Anak-Anak',
        ],
        
        [
            'id'=> 4,
            'name' => 'Aksesori',
            'slug' => 'Aksesori',
            'description' => 'Ini adalah produk aksesori',
            'image' => 'https://example.com/image4.jpg',
        ],

        [
            'id'=> 5,
            'name' => 'Sepatu',
            'slug' => 'Sepatu',
            'description' => 'Ini adalah produk sepatu',
            'image' => 'https://example.com/image5.jpg',
        ]
    ];
    
    public static function all()
    {
        return collect(self::$product_categories);
    }

    public static function find($slug){
        $categories = static::all();
        return $categories->firstWhere('slug', $slug);
    }
   
}
