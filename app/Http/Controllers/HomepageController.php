<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;

class HomepageController extends Controller
{
    public function index()
    {
        $categories = Categories::latest()->take(4)->get();
        $products   = Product::paginate(20);

        return view('web.homepage', [
            'categories' => $categories,
            'products'   => $products,
        ]);
    }

    public function products()
    {
        return view('web.products');
    }

    public function product($slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (! $product) {
            return abort(404);
        }

        $relatedProducts = Product::where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('web.product', [
            'slug'            => $slug,
            'product'         => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function categories()
    {
        return view('web.categories');
    }

    public function category($slug)
    {
        return view('web.category_by_slug', ['slug' => $slug]);
    }

    public function cart()
    {
        return view('web.cart');
    }

    public function checkout()
    {
        return view('web.checkout');
    }

}
