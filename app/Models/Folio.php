<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    use HasFactory;

    protected $table = 'folios';

    protected $fillable = [
        'reservation_id',
        'guest_id',
        'room_charge_total',
        'discount_total',
        'tax_total',
        'grand_total',
        'status',
        'meta',
    ];

    protected $casts = [
        'room_charge_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
