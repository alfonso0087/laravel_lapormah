<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ResponseFormatter;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $nim = $request->nim;
    $nama = $request->nama;
    $password = $request->password;
    if ($nim == null || $nama == null || $password == null) {
      return ResponseFormatter::error([
        'message' => 'Data tidak lengkap'
      ], 'Data tidak lengkap', 400);
    } else {
      $password = Hash::make($password);

      $mhs = new Mahasiswa();
      $mhs->nim = $nim;
      $mhs->nama = $nama;
      $mhs->password = $password;
      $mhs->save();

      return ResponseFormatter::success($mhs, 'Mahasiswa berhasil ditambahkan');
    }
  }

  public function login(Request $request)
  {
    $nim = $request->nim;
    $password = $request->password;

    if ($nim == null || $password == null) {
      return ResponseFormatter::error([
        'message' => 'Data tidak lengkap'
      ], 'Login Gagal', 400);
    } else {
      $mhs = Mahasiswa::where('nim', $nim)->first();

      if (!$mhs) {
        return ResponseFormatter::error([
          'message' => 'Data tidak ditemukan'
        ], 'Login Gagal', 400);
      } else {
        if (Hash::check($password, $mhs->password)) {
          if ($mhs->tokens->count() > 0) {
            $mhs->tokens()->delete();
          }

          return ResponseFormatter::success([
            'access_token' => $mhs->createToken('tokenMhs')->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $mhs
          ], 'Login Berhasil');
        } else {
          return ResponseFormatter::error([
            'message' => 'Password salah'
          ], 'Login Gagal', 400);
        }
      }
    }
  }

  public function logout(Request $request)
  {
    $mhs = $request->user();
    // Revoke user token.
    $mhs->currentAccessToken()->delete();
    return ResponseFormatter::success(
      ['message' => 'Logout berhasil'],
      'Logout berhasil'
    );
  }
}
