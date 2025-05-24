<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'url', 'duration', 'series_id'];

    
    public function series()
    {
        return $this->belongsTo(Series::class);
    }
}
