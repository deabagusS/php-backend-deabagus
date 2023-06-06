<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Motor;
use App\Models\Mobil;

class Kendaraan extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'kendaraan';

    protected $fillable = [
        'tahun_keluaran', 
        'warna', 
        'harga', 
        'mesin', 
        'kapasitas_penumpang', 
        'tipe', 
        'tipe_suspensi', 
        'tipe_transmisi', 
        'jenis'
    ];

    // public function motor()
    // {
    //     return $this->hasMany(Motor::class, 'kendaraan_id', 'id');
    // }

    // public function mobil()
    // {
    //     return $this->hasMany(Mobil::class, 'kendaraan_id', 'id');
    // }
}
