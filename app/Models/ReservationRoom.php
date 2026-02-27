<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationRoom extends Model
{
    use HasFactory;

    protected $table = 'reservation_rooms';

    protected $fillable = [
        'reservation_id',
        'room_id',
        'room_type_id',
        'rate_plan_named',
        'nightly_rate',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'nightly_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
