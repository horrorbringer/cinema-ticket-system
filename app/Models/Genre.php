<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(fn (Genre $genre) => $genre->slug ??= Str::slug($genre->name));
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genre');
    }
}
