<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategoryRelation;
use App\Models\ProductSupplier;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function index()
    {
        $data['products'] = Product::OrderBy('id', 'DESC')->get() ?? [];

        return view('admin.products.index', $data);
    }

    public function create()
    {
        $data['categories'] = Category::select('id as value', 'name as label')->get() ?? [];

        return view('admin.products.form', $data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.unique' => 'The product name must be unique.',
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.',
            'profit_margin.required' => 'The profit margin is required.',
            'categories.required' => 'Please select at least one category.',
            'categories.min' => 'Please select at least one category.',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images', 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'price' => $request->price,
                'final_price' => $request->final_price,
                'profit_type' => $request->profit_type == "amount" ? 2 : 1,
                'profit_margin' => $request->profit_margin,
                'image_path' => $imagePath
            ]);

            if (!empty($request->categories)) {
                foreach ($request->categories as $category) {
                    ProductCategoryRelation::create([
                        'category_id' => $category,
                        'product_id' => $product->id
                    ]);
                }
            }

            if (!empty($request->tags)) {
                foreach ($request->tags as $tag) {
                    ProductTag::create([
                        'product_id' => $product->id,
                        'name' => $tag
                    ]);
                }
            }
            if (!empty($request->suppliers)) {
                foreach ($request->suppliers as $supplier) {
                    ProductSupplier::create([
                        'product_id' => $product->id,
                        'name' => $supplier
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Product added successfully!');
            return redirect()->route('admin.products');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('update product' . $e->getMessage());
            session()->flash('error', 'Something went wrong! unable to add product.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $data['product'] = Product::where('id', $id)->with('tags', 'suppliers', 'categories')->first() ?? [];
        $data['categories'] = Category::select('id as value', 'name as label')->get() ?? [];

        return view('admin.products.form', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'price' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.unique' => 'The product name must be unique.',
            'name.required' => 'The product name is required.',
            'price.required' => 'The product price is required.',
            'profit_margin.required' => 'The profit margin is required.',
            'categories.required' => 'Please select at least one category.',
            'categories.min' => 'Please select at least one category.',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $imagePath = $request->file('image')->store('product_images', 'public');
                $product->image_path = $imagePath;
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'price' => $request->price,
                'profit_type' => $request->profit_type == "amount" ? 2 : 1,
                'profit_margin' => $request->profit_margin,
                'final_price' => $request->final_price,
            ]);

            ProductCategoryRelation::where('product_id', $id)->delete();
            ProductTag::where('product_id', $id)->delete();
            ProductSupplier::where('product_id', $id)->delete();

            if (!empty($request->categories)) {
                foreach ($request->categories as $category) {
                    ProductCategoryRelation::create([
                        'category_id' => $category,
                        'product_id' => $id
                    ]);
                }
            }

            if (!empty($request->tags)) {
                foreach ($request->tags as $tag) {
                    ProductTag::create([
                        'product_id' => $id,
                        'name' => $tag
                    ]);
                }
            }
            if (!empty($request->suppliers)) {
                foreach ($request->suppliers as $supplier) {
                    ProductSupplier::create([
                        'product_id' => $id,
                        'name' => $supplier
                    ]);
                }
            }

            DB::commit();

            session()->flash('success', 'Product updated successfully!');
            return redirect()->route('admin.products');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('update product' . $e->getMessage());
            session()->flash('error', 'Something went wrong! unable to update product.');
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        $data['product'] = Product::where('id', $id)->with('tags', 'suppliers', 'categories')->first() ?? [];
        return view('admin.products.show', $data);
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            if (empty($product)) {
                session()->flash('success', 'Product deleted successfully!');
            }

            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            ProductCategoryRelation::where('product_id', $id)->delete();
            ProductTag::where('product_id', $id)->delete();
            ProductSupplier::where('product_id', $id)->delete();

            $product->delete();
            DB::commit();

            session()->flash('success', 'Product deleted successfully!');
            return redirect()->route('admin.products');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('delete product' . $e->getMessage());
            session()->flash('error', 'Something went wrong! unable to delete product.');
            return redirect()->back();
        }
    }
}
