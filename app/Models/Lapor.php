<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

// use Illuminate\Database\Eloquent\Model;

class Lapor extends Model
{
  protected $connection = 'mongodb';
  protected $collection = 'lapor';

  protected $primaryKey = '_id';
  protected $keyType = 'string';

  protected $autoIncrement = false;
  protected $guarded;

  protected $with = ['category', 'mahasiswa'];


  public function category()
  {
    return $this->belongsTo(KategoriLaporan::class, 'code_category', 'kode');
  }

  public function mahasiswa()
  {
    return $this->belongsTo(mahasiswa::class, 'nim', 'nim');
  }
}
