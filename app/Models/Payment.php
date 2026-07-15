<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id', 'payment_method', 'transaction_id', 'amount', 'status', 'payload'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payload' => 'array',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
