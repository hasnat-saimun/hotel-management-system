<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'amount',
        'status',
        'due_date',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
