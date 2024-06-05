<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TfidfResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'datasets_id',
        'pre_processings_id',
        'term',
        'tfidf',
    ];

    protected $table = 'tfidf_results';

    public function pre_processings()
    {
        return $this->belongsTo(PreProcessing::class, 'pre_processings_id', 'id');
    }

}
