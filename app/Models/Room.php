<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Amenity;
use App\Models\RoomType;
use App\Models\Reservation;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type_id',
        'floor_id',
        'status',
        'notes',
        'is_active',
        'avatar'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_rooms')
            ->withPivot([
                'room_type_id',
                'rate_plan_named',
                'nightly_rate',
                'discount_amount',
                'tax_amount',
                'total_amount',
                'status',
            ])
            ->withTimestamps();
    }
}
