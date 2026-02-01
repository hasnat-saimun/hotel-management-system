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
        'number',
        'type',
        'status',
        'rate',
        'description',
        'floor',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // many-to-many for reservations that may include multiple rooms
    public function reservationMany()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_rooms');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'type', 'name');
    }
}
