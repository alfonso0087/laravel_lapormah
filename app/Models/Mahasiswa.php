<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class Mahasiswa extends Model
{
  use HasApiTokens;

  protected $connection = 'mongodb';
  protected $collection = 'mahasiswa';

  protected $guarded;
}
