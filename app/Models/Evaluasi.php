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
    public function getLabeledHasilAttribute()
    {
        if ($this->hasil < 0) {
            return 'negatif';
        } elseif ($this->hasil >= 0 && $this->hasil <= 1) {
            return 'netral';
        } else {
            return 'positif';
        }
    }

}
