<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBlock extends Model
{
    use HasFactory;

    protected $table = 'room_blocks';

    protected $fillable = [
        'group_name',
        'start_date',
        'end_date',
        'total_rooms',
        'status',
        'release_at',
        'released_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'release_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function roomBlockRooms()
    {
        return $this->hasMany(RoomBlockRoom::class, 'room_block_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_block_rooms')
            ->withPivot(['room_type_id', 'assigned_guest_id', 'status'])
            ->withTimestamps();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'room_block_id');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('status', '!=', 'cancelled')
            ->whereNull('released_at')
            ->where(function ($q) {
                $q->whereNull('release_at')->orWhere('release_at', '>', now());
            });
    }
}
