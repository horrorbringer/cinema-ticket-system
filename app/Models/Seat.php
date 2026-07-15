<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['hall_id', 'seat_type_id', 'row', 'number', 'label', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }
}
