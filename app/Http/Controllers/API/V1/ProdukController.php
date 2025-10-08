<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('Fetching products from database');

            $query = Produk::where('aktif', true);

            if ($request->has('kategori') && !empty($request->kategori)) {
                $query->where('kategori', $request->kategori);
            }

            $produk = $query->get();

            Log::info('Products found: ' . $produk->count());

            // Format response
            $formattedProducts = $produk->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'deskripsi' => $item->deskripsi,
                    'harga' => $item->harga,
                    'harga_formatted' => 'Rp ' . number_format($item->harga, 0, ',', '.'),
                    'kategori' => $item->kategori,
                    'gambar' => $item->image,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-product.jpg'),
                    'aktif' => $item->aktif
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $formattedProducts
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getByCategory($kategori)
    {
        try {
            Log::info('Fetching products by category: ' . $kategori);

            $produk = Produk::where('aktif', true)
                ->where('kategori', $kategori)
                ->get();

            $formattedProducts = $produk->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'deskripsi' => $item->deskripsi,
                    'harga' => $item->harga,
                    'harga_formatted' => 'Rp ' . number_format($item->harga, 0, ',', '.'),
                    'kategori' => $item->kategori,
                    'gambar' => $item->image,
                    'gambar_url' => $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-product.jpg'),
                    'aktif' => $item->aktif
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data produk kategori ' . $kategori . ' berhasil diambil',
                'data' => $formattedProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching products by category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk'
            ], 500);
        }
    }

    public function getKategories()
    {
        try {
            Log::info('Fetching categories');

            $kategories = Produk::where('aktif', true)
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->distinct()
                ->pluck('kategori')
                ->values();

            Log::info('Categories found: ' . $kategories->count());

            return response()->json([
                'success' => true,
                'message' => 'Data kategori berhasil diambil',
                'data' => $kategories
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $produk = Produk::where('aktif', true)->find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $formattedProduct = [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'deskripsi' => $produk->deskripsi,
                'harga' => $produk->harga,
                'harga_formatted' => 'Rp ' . number_format($produk->harga, 0, ',', '.'),
                'kategori' => $produk->kategori,
                'gambar' => $produk->image,
                'gambar_url' => $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/default-product.jpg'),
                'aktif' => $produk->aktif
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $formattedProduct
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk'
            ], 500);
        }
    }
}
