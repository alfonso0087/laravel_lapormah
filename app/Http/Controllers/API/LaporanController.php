<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $laporan = Lapor::all();
    if (count($laporan) > 0) {
      foreach ($laporan as $lapor) {
        if ($lapor->image != null) {
          $lapor->image = url('image_laporan/' . $lapor->code . '/' . $lapor->image);
        } else {
          $lapor->image = null;
        }
      }
      return response()->json([
        'status' => 'success',
        'message' => 'Data laporan berhasil diambil',
        'data' => $laporan
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Data laporan tidak ditemukan',
        'data' => null
      ], 404);
    }
  }

  public function getLaporanByMahasiswa(Request $request)
  {
    $nim = $request->nim;
    //! On local machine, this works:
    // $laporan = Lapor::where('nim', $nim)->get();

    //! On production server, this works:
    $laporan = Lapor::where('mahasiswa.nim', '==', $nim)->get();
    if (count($laporan) > 0) {
      foreach ($laporan as $lapor) {
        if ($lapor->image != null) {
          $lapor->image = url('image_laporan/' . $lapor->code . '/' . $lapor->image);
        } else {
          $lapor->image = null;
        }
      }
      return response()->json([
        'status' => 'success',
        'message' => 'Data laporan berhasil diambil',
        'data' => $laporan
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Data laporan tidak ditemukan',
        'data' => null
      ], 404);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $code = $this->generateCode();
    $nim = $request->nim;
    $code_category = $request->code_category;
    $description = $request->description;

    $image = $request->file('image');
    if ($image) {
      $image_name = $this->uploadImage($image, $code);
    } else {
      $image_name = null;
    }

    $lapor = new Lapor();
    $lapor->code = $code;
    $lapor->nim = $nim;
    $lapor->code_category = $code_category;
    $lapor->description = $description;
    $lapor->image = $image_name;
    $lapor->save();

    // Get image url if image is not null
    if ($lapor->image != null) {
      $lapor->image = url('image_laporan/' . $code . '/' . $image_name);
    } else {
      $lapor->image = null;
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Laporan berhasil ditambahkan',
      'data' => $lapor
    ], 200);
  }

  private function generateCode()
  {
    $code = Lapor::latest()->first();
    $string = 'LAPOR_';
    if ($code) {
      $code = explode('_', $code->code);
      $code = end($code);
      $code = (int) $code;
      $code = $code + 1;
      $code = str_pad($code, 3, '0', STR_PAD_LEFT);
    } else {
      $code = '001';
    }
    return $string . $code;
  }

  private function uploadImage($image, $code)
  {
    $image_name = $code . '.' . $image->getClientOriginalExtension();

    $path = public_path('image_laporan/' . $code);
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }

    Storage::disk('public')->put('image_laporan/' . $code . '/' . $image_name, file_get_contents($image));
    return $image_name;
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
    $code = $request->code;

    $lapor = Lapor::where('code', $code)->first();
    if ($lapor) {
      if ($lapor->image != null) {
        $lapor->image = url('image_laporan/' . $lapor->code . '/' . $lapor->image);
      } else {
        $lapor->image = null;
      }
      return response()->json([
        'status' => 'success',
        'message' => 'Data laporan berhasil diambil',
        'data' => $lapor
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Data laporan tidak ditemukan',
        'data' => null
      ], 404);
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $code = $request->code;
    $description = $request->description;
    $image = $request->file('image');

    $lapor = Lapor::where('code', $code)->first();

    if ($lapor) {
      if ($image) {
        $image_name = $this->uploadImage($image, $code);
        $lapor->image = $image_name;
      }
      $lapor->description = $description ?? $lapor->description;
      $lapor->save();

      if ($lapor->image != null) {
        $lapor->image = url('image_laporan/' . $lapor->code . '/' . $lapor->image);
      } else {
        $lapor->image = null;
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Data laporan berhasil diubah',
        'data' => $lapor
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Data laporan tidak ditemukan',
        'data' => null
      ], 404);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $code = $request->code;

    $lapor = Lapor::where('code', $code)->first();
    if ($lapor) {
      if ($lapor->image != null) {
        // Delete image
        Storage::disk('public')->delete('image_laporan/' . $code . '/' . $lapor->image);
      }
      $lapor->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'Data laporan berhasil dihapus',
        'data' => null
      ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Data laporan tidak ditemukan',
        'data' => null
      ], 404);
    }
  }
}
