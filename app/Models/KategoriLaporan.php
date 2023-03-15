<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

// use Illuminate\Database\Eloquent\Model;

class KategoriLaporan extends Model
{
  protected $connection = 'mongodb';
  protected $collection = 'kategori_laporan';

  protected $primaryKey = '_id';
  protected $keyType = 'string';

  protected $autoIncrement = false;
  protected $guarded;
}
