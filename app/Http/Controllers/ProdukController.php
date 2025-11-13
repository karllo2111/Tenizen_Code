<?php
// app/Http/Controllers/ProdukController.php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('idproduk', 'DESC')->get();
        
        $produk->transform(function ($item) {
            // Kembalikan URL barcode
            if ($item->barcode && Storage::exists('public/uploadproduk/' . $item->barcode)) {
                $item->barcode_url = asset('storage/uploadproduk/' . $item->barcode);
            } else {
                $item->barcode_url = asset('storage/uploadproduk/default.jpg');
            }
            return $item;
        });

        return response()->json(['result' => $produk]);
    }

    public function show($idproduk)
    {
        $produk = Produk::find($idproduk);

        if (!$produk) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        // Kembalikan URL barcode
        $barcodeUrl = null;
        if ($produk->barcode && Storage::exists('public/uploadproduk/' . $produk->barcode)) {
            $barcodeUrl = asset('storage/uploadproduk/' . $produk->barcode);
        } else {
            $barcodeUrl = asset('storage/uploadproduk/default.jpg');
        }

        return response()->json([
            'idproduk' => $produk->idproduk,
            'namaproduk' => $produk->namaproduk,
            'jumlah' => $produk->jumlah,
            'harga' => $produk->harga,
            'barcode' => $barcodeUrl
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idproduk' => 'required|unique:produk,idproduk',
            'namaproduk' => 'required',
            'jumlah' => 'required|integer',
            'harga' => 'required|numeric',
            'barcode' => 'required|string', // Masih terima base64 dari mobile
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        try {
            // Decode base64 dan simpan ke storage
            $imageData = base64_decode($request->barcode);
            $fileName = $request->idproduk . 'produk.jpg';
            $filePath = 'public/uploadproduk/' . $fileName;

            Storage::put($filePath, $imageData);

            $produk = Produk::create([
                'idproduk' => $request->idproduk,
                'namaproduk' => $request->namaproduk,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
                'barcode' => $fileName,
            ]);

            return response()->json([
                'message' => 'Berhasil menyimpan data produk',
                'data' => $produk
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $idproduk)
    {
        $produk = Produk::find($idproduk);

        if (!$produk) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'namaproduk' => 'required',
            'jumlah' => 'required|integer',
            'harga' => 'required|numeric',
            'barcode' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'namaproduk' => $request->namaproduk,
                'jumlah' => $request->jumlah,
                'harga' => $request->harga,
            ];

            if ($request->has('barcode') && $request->barcode) {
                // Hapus barcode lama jika ada
                if ($produk->barcode && Storage::exists('public/uploadproduk/' . $produk->barcode)) {
                    Storage::delete('public/uploadproduk/' . $produk->barcode);
                }

                // Simpan barcode baru
                $imageData = base64_decode($request->barcode);
                $fileName = $idproduk . 'produk.jpg';
                $filePath = 'public/uploadproduk/' . $fileName;

                Storage::put($filePath, $imageData);
                $updateData['barcode'] = $fileName;
            }

            $produk->update($updateData);

            return response()->json([
                'message' => 'Data produk berhasil diubah',
                'data' => $produk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengubah data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($idproduk)
    {
        $produk = Produk::find($idproduk);

        if (!$produk) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        try {
            // Hapus file barcode dari storage
            if ($produk->barcode && Storage::exists('public/uploadproduk/' . $produk->barcode)) {
                Storage::delete('public/uploadproduk/' . $produk->barcode);
            }

            $produk->delete();

            return response()->json([
                'message' => 'Data produk berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus data produk: ' . $e->getMessage()
            ], 500);
        }
    }
}