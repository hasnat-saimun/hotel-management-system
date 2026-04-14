<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBlockRoom extends Model
{
    use HasFactory;

    protected $table = 'room_block_rooms';

    protected $fillable = [
        'room_block_id',
        'room_id',
        'room_type_id',
        'assigned_guest_id',
        'reservation_id',
        'status',
    ];

    public function roomBlock()
    {
        return $this->belongsTo(RoomBlock::class, 'room_block_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function assignedGuest()
    {
        return $this->belongsTo(Guest::class, 'assigned_guest_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
