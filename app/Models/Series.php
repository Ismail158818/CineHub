<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'release_date', 'cast', 'description', 'type'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function types()
    {
        return $this->belongsToMany(Video::class);
    }
}
