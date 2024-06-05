<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembobotanKataKeterangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'weight'
    ];
    protected $table = 'bobot_kata_keterangan';


}
