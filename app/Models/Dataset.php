<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
enum Sentiment: string
{
    case Positif = 'positif';
    case Netral = 'netral';
    case Negatif = 'negatif';
}
class Dataset extends Model
{
    use HasFactory;
    protected $casts = [
        'sentiment' => Sentiment::class,
    ];
    protected $fillable = [
        'username',
        'full_text',
        'sentiment',
    ];

    protected $table = 'datasets';
}
