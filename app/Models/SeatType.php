<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'price_multiplier'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
