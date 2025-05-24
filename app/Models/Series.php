<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'date', 'cast', 'description','rating','image'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function types()
    {
        return $this->belongsToMany(Type::class);
    }
}
