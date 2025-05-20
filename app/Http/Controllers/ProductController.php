<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $query = Product::with('category');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $products = $query->paginate(10)->withQueryString();

        return view('dashboard.products.index', compact('products', 'q'));
    }

    public function create()
    {
        $categories = Categories::all();
        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[] = ['value' => $category->id, 'label' => $category->name];
        }
        return view('dashboard.products.create', compact('categories', 'categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999999999',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = $path;
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = true;

        Product::create($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Categories::all();
        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[] = ['value' => $category->id, 'label' => $category->name];
        }
        return view('dashboard.products.edit', compact('product', 'categories', 'categoryOptions'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999999999',
            'stock' => 'required|integer|min:0',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = $path;
        }

        // Use slug from request instead of generating from name
        //$validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Product deleted successfully.');
    }
}
