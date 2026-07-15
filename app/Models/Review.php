<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'user_id', 'rating', 'review', 'approved'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'approved' => 'boolean',
        ];
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
