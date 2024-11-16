<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'duration', 'image', 'link', 'cast', 'rating'
    ];

    public function types()
    {
        return $this->belongsToMany(Type::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
