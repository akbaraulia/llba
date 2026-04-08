<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('q')->toString();

        $products = Product::query()
            ->when($search, function ($query, $searchValue) {
                $query->where('name', 'like', "%{$searchValue}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        Product::create([
            'name' => $data['name'],
            'price' => $this->moneyToNumber($data['price']),
            'stock' => $data['stock'],
            'image_path' => $request->file('image')?->store('products', 'public'),
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $updateData = [
            'name' => $data['name'],
            'price' => $this->moneyToNumber($data['price']),
        ];

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $updateData['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('products.index')
                ->withErrors($validator, 'stock')
                ->withInput()
                ->with('open_stock_modal', $product->id);
        }

        $product->update([
            'stock' => (int) $request->input('stock'),
        ]);

        return redirect()->route('products.index')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function moneyToNumber(string $value): string
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            return '0';
        }

        if (preg_match('/^\d+\.\d{1,2}$/', $trimmed) === 1) {
            return (string) ((int) round((float) $trimmed));
        }

        $numeric = preg_replace('/[^0-9]/', '', $trimmed);

        return (string) ($numeric ?: 0);
    }
}
