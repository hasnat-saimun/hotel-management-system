<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'reservations';

    protected $fillable = [
        'guest_id',
        'room_block_id',
        'reservation_code',
        'channel',
        'status',
        'payment_status',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'rate',
        'extras',
        'note',
        'cancel_note',
    ];

    protected $casts = [
        'extras' => 'array',
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'reservation_rooms')
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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'reservation_guests')
            ->withPivot(['is_primary'])
            ->withTimestamps();
    }

    public function roomBlock()
    {
        return $this->belongsTo(RoomBlock::class, 'room_block_id');
    }

    public function folio()
    {
        return $this->hasOne(Folio::class);
    }

    public function reservationRooms()
    {
        return $this->hasMany(ReservationRoom::class);
    }

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }
}
