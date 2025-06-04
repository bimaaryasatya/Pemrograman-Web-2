<?php
namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::all();

        return view('dashboard.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name',
            'slug'        => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path               = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        // If slug is not provided, generate from name
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        \App\Models\Categories::create($validated);

        return redirect()->route('categories.index')->with('successMessage', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Categories::findOrFail($id);
        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Categories::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name,' . $category->id,
            'slug'        => 'nullable|string|max:255|unique:product_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path               = $request->file('image')->store('categories', 'public');
            $validated['image'] = $path;
        }

        // If slug is not provided, generate from name
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('successMessage', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Categories::find($id);
        $category->delete();
        return redirect()->back()
            ->with(
                [
                    'successMessage' => 'Data Berhasil Dihapus',
                ]
            );
    }
}
