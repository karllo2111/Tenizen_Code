<?php
// app/Http/Controllers/SiswaController.php
namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        
        $siswa->transform(function ($item) {
            // Kembalikan URL foto
            if ($item->foto && Storage::exists('public/upload/' . $item->foto)) {
                $item->foto_url = asset('storage/upload/' . $item->foto);
            } else {
                $item->foto_url = asset('storage/upload/default.jpg');
            }
            return $item;
        });

        return response()->json([
            'result' => $siswa
        ]);
    }

    public function show($nis)
    {
        $siswa = Siswa::find($nis);

        if (!$siswa) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        // Kembalikan URL foto
        $fotoUrl = null;
        if ($siswa->foto && Storage::exists('public/upload/' . $siswa->foto)) {
            $fotoUrl = asset('storage/upload/' . $siswa->foto);
        } else {
            $fotoUrl = asset('storage/upload/default.jpg');
        }

        return response()->json([
            'nis' => $siswa->nis,
            'namasiswa' => $siswa->namasiswa,
            'jk' => $siswa->jk,
            'alamat' => $siswa->alamat,
            'tanggallahir' => $siswa->tanggallahir,
            'foto' => $fotoUrl
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|unique:siswa,nis',
            'namasiswa' => 'required',
            'jk' => 'required|in:L,P',
            'alamat' => 'required',
            'tanggallahir' => 'required|date',
            'foto' => 'required|string', // Masih terima base64 dari mobile
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        try {
            // Decode base64 dan simpan ke storage
            $imageData = base64_decode($request->foto);
            $fileName = $request->nis . '_siswa.jpg';
            $filePath = 'public/upload/' . $fileName;

            Storage::put($filePath, $imageData);

            $siswa = Siswa::create([
                'nis' => $request->nis,
                'namasiswa' => $request->namasiswa,
                'jk' => $request->jk,
                'alamat' => $request->alamat,
                'tanggallahir' => $request->tanggallahir,
                'foto' => $fileName,
            ]);

            return response()->json([
                'message' => 'Berhasil menyimpan data',
                'data' => $siswa
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $nis)
    {
        $siswa = Siswa::find($nis);

        if (!$siswa) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'namasiswa' => 'required',
            'jk' => 'required|in:L,P',
            'alamat' => 'required',
            'tanggallahir' => 'required|date',
            'foto' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'namasiswa' => $request->namasiswa,
                'jk' => $request->jk,
                'alamat' => $request->alamat,
                'tanggallahir' => $request->tanggallahir,
            ];

            if ($request->has('foto') && $request->foto) {
                // Hapus foto lama jika ada
                if ($siswa->foto && Storage::exists('public/upload/' . $siswa->foto)) {
                    Storage::delete('public/upload/' . $siswa->foto);
                }

                // Simpan foto baru
                $imageData = base64_decode($request->foto);
                $fileName = $nis . '_siswa.jpg';
                $filePath = 'public/upload/' . $fileName;

                Storage::put($filePath, $imageData);
                $updateData['foto'] = $fileName;
            }

            $siswa->update($updateData);

            return response()->json([
                'message' => 'Data berhasil diubah',
                'data' => $siswa
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengubah data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($nis)
    {
        $siswa = Siswa::find($nis);

        if (!$siswa) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        try {
            // Hapus file foto dari storage
            if ($siswa->foto && Storage::exists('public/upload/' . $siswa->foto)) {
                Storage::delete('public/upload/' . $siswa->foto);
            }

            $siswa->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}