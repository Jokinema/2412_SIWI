<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pre_processings_id',
        'hasil'
    ];

    protected $table = 'evaluasi';
    public function preprocess()
    {
        return $this->belongsTo(PreProcessing::class, 'pre_processings_id', 'id');
    }
}
