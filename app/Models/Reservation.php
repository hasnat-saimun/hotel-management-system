<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name',
        'guest_email',
        'room_id',
        'room_number',
        'check_in_date',
        'check_out_date',
        'status',
        'rate',
        'extras',
        'channel',
        'payment_status',
    ];

    protected $casts = [
        'extras' => 'array',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'reservation_rooms');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
