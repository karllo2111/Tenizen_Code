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
        return response()->json(['result' => $produk]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idproduk' => 'required|unique:produk,idproduk',
            'namaproduk' => 'required',
            'jumlah' => 'required|integer',
            'harga' => 'required|numeric',
            'barcode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        try {
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
}