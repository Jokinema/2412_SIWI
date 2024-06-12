<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembobotanLexicon extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'weight',
        'number_of_words'
    ];

    protected $table = 'bobot_lexicon';

    public function getLabeledBobotAttribute()
    {
        if ($this->weight < 0) {
            return 'negatif';
        } elseif ($this->weight >= 0 && $this->weight <= 1) {
            return 'netral';
        } else {
            return 'positif';
        }
    }

}
