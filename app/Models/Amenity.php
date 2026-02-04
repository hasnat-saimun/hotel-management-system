<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rooms;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms()
    {
        return $this->belongsToMany(
            Rooms::class,
            'amenity_room',
            'amenity_id',
            'room_id'
        )->withTimestamps();
    }

    // public function roomTypes()
    // {
    //     return $this->rooms();
    // }
}
