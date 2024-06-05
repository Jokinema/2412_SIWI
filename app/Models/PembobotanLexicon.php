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
}
