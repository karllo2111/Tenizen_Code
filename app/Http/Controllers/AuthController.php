<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari user di tabel 'user' (bukan 'users')
        $user = User::where('email', $request->email)->first();

        // Gunakan MD5 seperti di file PHP native Anda
        if (!$user || md5($request->password) !== $user->password) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'status' => 'sukses',
            'message' => 'Login berhasil',
            'data' => [
                'iduser' => $user->iduser,
                'username' => $user->username,
                'email' => $user->email,
            ]
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:user,email', // PASTIKAN 'user' bukan 'users'
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => md5($request->password), // Gunakan MD5
        ]);

        return response()->json([
            'message' => 'User berhasil didaftarkan',
            'data' => $user
        ], 201);
    }
}