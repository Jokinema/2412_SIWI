<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreProcessing extends Model
{
    use HasFactory;

    protected $fillable = [
        'datasets_id',
        'original',
        'cleaned',
        'case_folded',
        'tokenized',
    ];

    protected $table = 'pre_processings';
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'datasets_id', 'id');
    }
}
