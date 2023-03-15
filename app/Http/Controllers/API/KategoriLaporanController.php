<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KategoriLaporan;
use Illuminate\Http\Request;
use ResponseFormatter;

class KategoriLaporanController extends Controller
{
  public function index()
  {
    $kategoriLaporan = KategoriLaporan::all();
    if (count($kategoriLaporan) > 0) {
      return ResponseFormatter::success(
        ['kategori_laporan' => $kategoriLaporan],
        'Data kategori laporan berhasil diambil'
      );
    } else {
      return ResponseFormatter::error([
        'message' => 'Data kategori laporan tidak ditemukan'
      ], 'Data kategori laporan tidak ditemukan', 400);
    }
  }

  public function store(Request $request)
  {
    $category_name = $request->category_name;
    if ($category_name == null) {
      return ResponseFormatter::error([
        'message' => 'Data tidak lengkap'
      ], 'Data tidak lengkap', 400);
    } else {
      $category = KategoriLaporan::where('category_name', $category_name)->first();
      if ($category) {
        return ResponseFormatter::error([
          'message' => 'Kategori laporan sudah ada'
        ], 'Kategori laporan sudah ada', 400);
      }
      $code_category = $this->generateCodeCategory();
      $kategoriLaporan = new KategoriLaporan();

      $kategoriLaporan->nama_kategori = $category_name;
      $kategoriLaporan->kode = $code_category;
      $kategoriLaporan->save();

      return ResponseFormatter::success($kategoriLaporan, 'Kategori laporan berhasil ditambahkan');
    }
  }

  private function generateCodeCategory()
  {
    $code_category = KategoriLaporan::latest()->first();
    if ($code_category) {
      $code = $code_category->kode;
      $code = (int) $code;
      $code = $code + 1;
      $code = str_pad($code, 3, '0', STR_PAD_LEFT);
    } else {
      $code = '001';
    }
    return $code;
  }

  public function show(Request $request)
  {
    $id_category = $request->id_category;
    $kategoriLaporan = KategoriLaporan::find($id_category);
    if ($kategoriLaporan) {
      return ResponseFormatter::success(
        ['kategori' => $kategoriLaporan],
        'Data kategori laporan berhasil diambil'
      );
    } else {
      return ResponseFormatter::error([
        'message' => 'Data kategori laporan tidak ditemukan'
      ], 'Data kategori laporan tidak ditemukan', 400);
    }
  }

  public function update(Request $request, $id)
  {
    $category_name = $request->category_name;
    if ($category_name == null) {
      return ResponseFormatter::error([
        'message' => 'Data tidak lengkap'
      ], 'Data tidak lengkap', 400);
    } else {
      $kategoriLaporan = KategoriLaporan::find($id);
      $kategoriLaporan->nama_kategori = $category_name;
      $kategoriLaporan->save();

      return ResponseFormatter::success($kategoriLaporan, 'Kategori laporan berhasil diubah');
    }
  }

  public function destroy($id)
  {
    $kategoriLaporan = KategoriLaporan::find($id);
    if (!$kategoriLaporan) {
      return ResponseFormatter::error([
        'message' => 'Data kategori laporan tidak ditemukan'
      ], 'Data kategori laporan tidak ditemukan', 400);
    } else {
      $kategoriLaporan->delete();
      return ResponseFormatter::success('', 'Kategori laporan berhasil dihapus');
    }
  }
}
